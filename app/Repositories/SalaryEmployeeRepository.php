<?php

namespace App\Repositories;

use App\Enums\StatusSalaryEmployeeEnum;
use App\Models\SablonEmployeeDetail;
use App\Models\SalaryEmployee;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class SalaryEmployeeRepository
{
    public function getAll(string $filter = 'all', ?string $search = null, int $perPage = 12, ?string $weekOf = null)
    {
        [$start, $end] = $this->resolveWeekRange($weekOf);

        $existingSalaries = SalaryEmployee::query()
            ->with([
                'employee',
                'sablonEmployeeDetails.sablon.supplier',
                'sablonEmployeeDetails.sablon.imageFabric',
            ])
            ->where('date', $start)
            ->get();

        $existingEmployeeIds = $existingSalaries->pluck('employee_id')->all();

        $eligibleDetails = SablonEmployeeDetail::query()
            ->whereNull('salary_employee_id')
            ->whereNotIn('employee_id', $existingEmployeeIds)
            ->eligibleForSalary()
            ->whereHas('sablon', fn($q) => $q->whereBetween('date_sablon', [$start, $end]))
            ->with(['sablon.supplier', 'sablon.imageFabric', 'employee'])
            ->get()
            ->groupBy('employee_id');

        $virtualSalaries = $eligibleDetails->map(function ($details) use ($start) {
            $totalFee = $details->sum(fn(SablonEmployeeDetail $d) => (float) $d->fee);

            $salary = new SalaryEmployee([
                'employee_id'    => $details->first()->employee_id,
                'fee'            => $totalFee,
                'additional_fee' => [],
                'status'         => StatusSalaryEmployeeEnum::PENDING,
                'date'           => $start,
                'notes'          => null,
            ]);

            $salary->setRelation('employee', $details->first()->employee);
            $salary->setRelation('sablonEmployeeDetails', $details);

            return $salary;
        })->values();

        $collection = $existingSalaries->concat($virtualSalaries);

        if ($filter !== 'all') {
            $collection = $collection->filter(fn($s) => $s->status?->value === $filter);
        }

        if ($search) {
            $needle     = mb_strtolower($search);
            $collection = $collection->filter(
                fn($s) => str_contains(mb_strtolower($s->employee?->name ?? ''), $needle)
            );
        }

        $collection = $collection->sortBy(fn($s) => $s->employee?->name)->values();

        $page  = (int) request('page', 1);
        $items = $collection->forPage($page, $perPage)->values();

        return new LengthAwarePaginator(
            $items,
            $collection->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    private function resolveWeekRange(?string $weekOf): array
    {
        $reference = $weekOf ? Carbon::parse($weekOf) : Carbon::now();

        return [
            $reference->copy()->startOfWeek(Carbon::MONDAY)->toDateString(),
            $reference->copy()->endOfWeek(Carbon::SUNDAY)->toDateString(),
        ];
    }
}
