<?php

namespace App\Repositories;

use App\Enums\StatusSalaryEmployeeEnum;
use App\Models\Memo;
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
                'memos',
            ])
            ->whereBetween('date', [$start, $end])
            ->get();

        $existingKeys = $existingSalaries
            ->map(fn($s) => $s->employee_id . '|' . Carbon::parse($s->date)->toDateString())
            ->all();

        $eligibleDetails = SablonEmployeeDetail::query()
            ->whereNull('salary_employee_id')
            ->eligibleForSalary()
            ->inWeek($start, $end)
            ->with(['sablon.supplier', 'sablon.imageFabric', 'employee'])
            ->get()
            ->groupBy(function (SablonEmployeeDetail $detail) {
                $weekStart = Carbon::parse($detail->weekAnchorDate())
                    ->startOfWeek(Carbon::MONDAY)
                    ->toDateString();

                return $detail->employee_id . '|' . $weekStart;
            });

        $pendingMemosByEmployee = Memo::query()
            ->eligibleForSalary()
            ->whereNull('salary_employee_id')
            ->get()
            ->groupBy('employee_id');

        $presences = Presence::query()
            ->whereBetween('week_of', [$start, $end])
            ->get()
            ->keyBy(fn($p) => $p->employee_id . '|' . Carbon::parse($p->week_of)->toDateString());

        $allDetails = SablonEmployeeDetail::query()
            ->whereNull('salary_employee_id')
            ->inWeek($start, $end)
            ->with(['sablon.supplier', 'sablon.imageFabric', 'employee'])
            ->get()
            ->groupBy(function (SablonEmployeeDetail $detail) {
                $weekStart = Carbon::parse($detail->weekAnchorDate())
                    ->startOfWeek(Carbon::MONDAY)
                    ->toDateString();

                return $detail->employee_id . '|' . $weekStart;
            });

        $existingSalaries->each(function (SalaryEmployee $s) use ($presences, $allDetails, $pendingMemosByEmployee) {
            $key = $s->employee_id . '|' . Carbon::parse($s->date)->toDateString();

            if ($s->status !== StatusSalaryEmployeeEnum::PAID) {
                if ($allDetails->has($key)) {
                    $s->setRelation(
                        'sablonEmployeeDetails',
                        $s->sablonEmployeeDetails->concat($allDetails->get($key))
                    );
                }

                if ($pendingMemosByEmployee->has($s->employee_id)) {
                    $s->setRelation(
                        'memos',
                        $s->memos->concat($pendingMemosByEmployee->get($s->employee_id))
                    );
                }
            }

            $s->setRelation('presence', $presences->get($key));
        });

        $virtualSalaries = $eligibleDetails
            ->reject(fn($details, $key) => in_array($key, $existingKeys))
            ->map(function ($details, $key) use ($presences, $pendingMemosByEmployee, $allDetails) {
                $totalFee = $details->sum(fn(SablonEmployeeDetail $d) => $d->countableAmount());
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
                $salary->setRelation('sablonEmployeeDetails', $allDetails->get($key, $details));
                $salary->setRelation('presence', $presences->get($first->employee_id . '|' . $weekStart));
                $salary->setRelation('memos', $pendingMemosByEmployee->get($first->employee_id, collect()));

                return $salary;
            })
            ->values();

        $existingEmployeeIds = $existingSalaries->pluck('employee_id')
            ->merge($virtualSalaries->pluck('employee_id'))
            ->unique();

        $memoOnlyVirtualSalaries = $pendingMemosByEmployee
            ->reject(fn($memos, $employeeId) => $existingEmployeeIds->contains($employeeId))
            ->map(function ($memos, $employeeId) use ($presences) {
                $first = $memos->first();

                $salary = new SalaryEmployee([
                    'employee_id'    => $employeeId,
                    'fee'            => 0,
                    'additional_fee' => [],
                    'status'         => StatusSalaryEmployeeEnum::PENDING,
                    'date'           => now()->startOfWeek(Carbon::MONDAY)->toDateString(),
                    'notes'          => null,
                ]);

                $salary->setRelation('employee', $first->employee);
                $salary->setRelation('sablonEmployeeDetails', collect());
                $salary->setRelation('presence', $presences->get(
                    $employeeId . '|' . now()->startOfWeek(Carbon::MONDAY)->toDateString()
                ));
                $salary->setRelation('memos', $memos);

                return $salary;
            })
            ->values();

        $collection = $existingSalaries
            ->concat($virtualSalaries)
            ->concat($memoOnlyVirtualSalaries);

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
