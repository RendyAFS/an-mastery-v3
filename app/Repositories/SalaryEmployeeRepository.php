<?php

namespace App\Repositories;

use App\Enums\StatusSalaryEmployeeEnum;
use App\Models\Presence;
use App\Models\SablonEmployeeDetail;
use App\Models\SalaryEmployee;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class SalaryEmployeeRepository
{
    public function getAll(
        string $filter = 'all',
        ?string $search = null,
        int $perPage = 12,
        ?Carbon $dateFrom = null,
        ?Carbon $dateTo = null
    ) {
        [$start, $end] = $this->resolveWeekRange($dateFrom, $dateTo);

        $existingSalaries = SalaryEmployee::query()
            ->with([
                'employee',
                'sablonEmployeeDetails.sablon.supplier',
                'sablonEmployeeDetails.sablon.imageFabric',
            ])
            ->whereBetween('date', [$start, $end])
            ->get();

        $existingKeys = $existingSalaries
            ->map(fn($s) => $s->employee_id . '|' . Carbon::parse($s->date)->toDateString())
            ->all();

        $eligibleDetails = SablonEmployeeDetail::query()
            ->whereNull('salary_employee_id')
            ->eligibleForSalary()
            ->whereHas('sablon', fn($q) => $q->whereBetween('date_sablon', [$start, $end]))
            ->with(['sablon.supplier', 'sablon.imageFabric', 'employee'])
            ->get()
            ->groupBy(function (SablonEmployeeDetail $detail) {
                $weekStart = Carbon::parse($detail->sablon->date_sablon)
                    ->startOfWeek(Carbon::MONDAY)
                    ->toDateString();

                return $detail->employee_id . '|' . $weekStart;
            });

        $presences = Presence::query()
            ->whereBetween('week_of', [$start, $end])
            ->get()
            ->keyBy(fn($p) => $p->employee_id . '|' . Carbon::parse($p->week_of)->toDateString());

        $existingSalaries->each(function (SalaryEmployee $s) use ($presences) {
            $key = $s->employee_id . '|' . Carbon::parse($s->date)->toDateString();
            $s->setRelation('presence', $presences->get($key));
        });

        $virtualSalaries = $eligibleDetails
            ->reject(fn($details, $key) => in_array($key, $existingKeys))
            ->map(function ($details) use ($presences) {
                $totalFee = $details->sum(fn(SablonEmployeeDetail $d) => (float) $d->fee);
                $first    = $details->first();

                $weekStart = Carbon::parse($first->sablon->date_sablon)
                    ->startOfWeek(Carbon::MONDAY)
                    ->toDateString();

                $salary = new SalaryEmployee([
                    'employee_id'    => $first->employee_id,
                    'fee'            => $totalFee,
                    'additional_fee' => [],
                    'status'         => StatusSalaryEmployeeEnum::PENDING,
                    'date'           => $weekStart,
                    'notes'          => null,
                ]);

                $salary->setRelation('employee', $first->employee);
                $salary->setRelation('sablonEmployeeDetails', $details);
                $salary->setRelation('presence', $presences->get($first->employee_id . '|' . $weekStart));

                return $salary;
            })
            ->values();

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

        $collection = $collection
            ->sortByDesc(fn($s) => Carbon::parse($s->date)->format('Y-m-d'))
            ->values();

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

    private function resolveWeekRange(?Carbon $dateFrom, ?Carbon $dateTo): array
    {
        if ($dateFrom && $dateTo) {
            return [$dateFrom->toDateString(), $dateTo->toDateString()];
        }

        $reference = Carbon::now();

        return [
            $reference->copy()->startOfWeek(Carbon::MONDAY)->toDateString(),
            $reference->copy()->endOfWeek(Carbon::SUNDAY)->toDateString(),
        ];
    }
}
