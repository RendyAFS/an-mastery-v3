<?php

namespace App\Actions\SalaryEmployee;

use App\Enums\StatusSalaryEmployeeEnum;
use App\Models\Memo;
use App\Models\Presence;
use App\Models\Sablon;
use App\Models\SablonEmployeeDetail;
use App\Models\SalaryEmployee;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UpsertSalaryEmployeeAction
{
    public function handleForEmployee(
        int $employeeId,
        string $weekOf,
        ?string $status = null,
        ?array $additionalFee = null,
        ?string $notes = null,
        bool $appendAdditionalFee = false
    ): SalaryEmployee {
        return DB::transaction(function () use ($employeeId, $weekOf, $status, $additionalFee, $notes, $appendAdditionalFee) {
            $start = Carbon::parse($weekOf)->startOfWeek(Carbon::MONDAY)->toDateString();
            $end   = Carbon::parse($weekOf)->endOfWeek(Carbon::SUNDAY)->toDateString();

            $salary = SalaryEmployee::firstOrNew([
                'employee_id' => $employeeId,
                'date'        => $start,
            ]);

            if (! $salary->exists) {
                $salary->status         = StatusSalaryEmployeeEnum::PENDING;
                $salary->fee            = 0;
                $salary->additional_fee = [];
            }

            $wasPaid = $salary->exists && $salary->status === StatusSalaryEmployeeEnum::PAID;

            $alreadyLinkedDetails = $salary->exists
                ? SablonEmployeeDetail::query()
                ->where('salary_employee_id', $salary->id)
                ->get()
                : collect();

            $newEligibleDetails = SablonEmployeeDetail::query()
                ->where('employee_id', $employeeId)
                ->whereNull('salary_employee_id')
                ->eligibleForSalary()
                ->inWeek($start, $end)
                ->get();

            $alreadyLinkedMemos = $salary->exists
                ? Memo::query()->where('salary_employee_id', $salary->id)->get()
                : collect();

            $newEligibleMemos = Memo::query()
                ->where('employee_id', $employeeId)
                ->whereNull('salary_employee_id')
                ->eligibleForSalary()
                ->whereBetween('date', [$start, $end])
                ->get();

            $hasNewData = $newEligibleDetails->isNotEmpty() || $newEligibleMemos->isNotEmpty();

            $eligibleDetails = $alreadyLinkedDetails->concat($newEligibleDetails);
            $totalFee = $eligibleDetails->sum(fn(SablonEmployeeDetail $d) => $d->countableAmount());

            $eligibleMemos = $alreadyLinkedMemos->concat($newEligibleMemos);
            $memoTotal = $eligibleMemos->sum(fn(Memo $m) => (int) $m->nominal);

            $presenceTotal = (float) (Presence::where('employee_id', $employeeId)
                ->where('week_of', $start)
                ->value('total') ?? 0);

            $requestedStatus = $status !== null ? StatusSalaryEmployeeEnum::from($status) : null;

            $additionalFeeSum = collect($additionalFee ?? ($salary->additional_fee ?? []))
                ->sum(fn($af) => (float) ($af['nominal'] ?? 0));

            $computedTotal = $totalFee + $presenceTotal + $memoTotal + $additionalFeeSum;

            if (! $salary->exists && $requestedStatus === null && $computedTotal == 0) {
                return $salary;
            }

            if ($wasPaid && $hasNewData && $requestedStatus !== StatusSalaryEmployeeEnum::PAID) {
                $salary->status = StatusSalaryEmployeeEnum::PENDING;
            } elseif ($requestedStatus !== null) {
                $salary->status = $requestedStatus;
            }

            if ($additionalFee !== null) {
                $normalized = array_map(function ($af) {
                    $item = [
                        'nominal' => (float) ($af['nominal'] ?? 0),
                        'notes'   => (string) ($af['notes'] ?? ''),
                    ];
                    if (! empty($af['type'])) {
                        $item['type'] = (string) $af['type'];
                    }

                    return $item;
                }, $additionalFee);

                $salary->additional_fee = $appendAdditionalFee
                    ? array_values(array_merge($salary->additional_fee ?? [], $normalized))
                    : array_values($normalized);
            }

            if ($notes !== null) {
                $salary->notes = $notes;
            }

            $salary->fee = $totalFee;

            $salary->save();

            SablonEmployeeDetail::whereIn('id', $eligibleDetails->pluck('id'))
                ->update(['salary_employee_id' => $salary->id]);

            Memo::whereIn('id', $eligibleMemos->pluck('id'))
                ->update(['salary_employee_id' => $salary->id]);

            if ($salary->status === StatusSalaryEmployeeEnum::PAID) {
                SablonEmployeeDetail::where('salary_employee_id', $salary->id)
                    ->update(['is_paid' => true]);

                Memo::where('salary_employee_id', $salary->id)
                    ->update(['is_paid' => true]);

                if ($requestedStatus === StatusSalaryEmployeeEnum::PAID) {
                    $this->cascadePreviousPendingToPaid($salary);
                }
            } else {
                SablonEmployeeDetail::where('salary_employee_id', $salary->id)
                    ->update(['is_paid' => false]);

                Memo::where('salary_employee_id', $salary->id)
                    ->update(['is_paid' => false]);
            }

            return $salary->fresh([
                'employee',
                'sablonEmployeeDetails.sablon.supplier',
                'sablonEmployeeDetails.sablon.imageFabric',
                'memos',
            ]);
        });
    }

    public function reopenIfPaid(SalaryEmployee $salary): void
    {
        if ($salary->status !== StatusSalaryEmployeeEnum::PAID) {
            return;
        }

        $salary->status = StatusSalaryEmployeeEnum::PENDING;
        $salary->save();

        SablonEmployeeDetail::where('salary_employee_id', $salary->id)
            ->update(['is_paid' => false]);

        Memo::where('salary_employee_id', $salary->id)
            ->update(['is_paid' => false]);
    }

    public function handleBulk(Carbon $dateFrom, Carbon $dateTo): int
    {
        $start = $dateFrom->copy()->startOfWeek(Carbon::MONDAY);
        $end   = $dateTo->copy()->endOfWeek(Carbon::SUNDAY);

        $pairs = SablonEmployeeDetail::query()
            ->whereNull('salary_employee_id')
            ->eligibleForSalary()
            ->inWeek($start->toDateString(), $end->toDateString())
            ->with('sablon')
            ->get()
            ->map(function (SablonEmployeeDetail $detail) {
                $weekStart = Carbon::parse($detail->weekAnchorDate())
                    ->startOfWeek(Carbon::MONDAY)
                    ->toDateString();

                return $detail->employee_id . '|' . $weekStart;
            })
            ->unique();

        foreach ($pairs as $pair) {
            [$employeeId, $weekStart] = explode('|', $pair);
            $this->handleForEmployee((int) $employeeId, $weekStart);
        }

        return $pairs->count();
    }

    private function cascadePreviousPendingToPaid(SalaryEmployee $salary): void
    {
        $previousPending = SalaryEmployee::query()
            ->where('employee_id', $salary->employee_id)
            ->where('status', StatusSalaryEmployeeEnum::PENDING)
            ->where('date', '<', $salary->date)
            ->get();

        foreach ($previousPending as $prev) {
            $prev->status = StatusSalaryEmployeeEnum::PAID;
            $prev->save();

            SablonEmployeeDetail::where('salary_employee_id', $prev->id)
                ->update(['is_paid' => true]);

            Memo::where('salary_employee_id', $prev->id)
                ->update(['is_paid' => true]);
        }
    }

    public function syncSablonEmployeeDetails(Sablon $sablon): void
    {
        $details = $sablon->sablonEmployeeDetails()->with('salaryEmployee')->get();

        if ($details->isEmpty()) {
            return;
        }

        $pairs = collect();

        foreach ($details as $detail) {
            $anchor = $detail->weekAnchorDate();

            if (! $anchor) {
                continue;
            }

            $correctWeekStart = Carbon::parse($anchor)->startOfWeek(Carbon::MONDAY)->toDateString();

            if ($detail->salary_employee_id && $detail->salaryEmployee) {
                $currentWeekStart = Carbon::parse($detail->salaryEmployee->date)->toDateString();

                if ($currentWeekStart !== $correctWeekStart) {
                    if ($detail->salaryEmployee->status === StatusSalaryEmployeeEnum::PAID) {
                        continue;
                    }

                    $pairs->push($detail->employee_id . '|' . $currentWeekStart);
                    $detail->update(['salary_employee_id' => null]);
                }
            }

            $pairs->push($detail->employee_id . '|' . $correctWeekStart);
        }

        $pairs->unique()->each(function ($pair) {
            [$employeeId, $weekStart] = explode('|', $pair);
            $this->handleForEmployee((int) $employeeId, $weekStart);
        });
    }

    public function realignMisplacedDetails(): Collection
    {
        $misplaced = SablonEmployeeDetail::query()
            ->whereNotNull('salary_employee_id')
            ->with('salaryEmployee')
            ->get()
            ->filter(fn(SablonEmployeeDetail $detail) => $detail->salaryEmployee
                && $detail->salaryEmployee->status !== StatusSalaryEmployeeEnum::PAID)
            ->filter(function (SablonEmployeeDetail $detail) {
                $anchor = $detail->weekAnchorDate();

                if (! $anchor) {
                    return false;
                }

                $correctWeekStart = Carbon::parse($anchor)->startOfWeek(Carbon::MONDAY)->toDateString();
                $currentWeekStart = Carbon::parse($detail->salaryEmployee->date)->toDateString();

                return $correctWeekStart !== $currentWeekStart;
            });

        $pairs = collect();

        foreach ($misplaced as $detail) {
            $currentWeekStart = Carbon::parse($detail->salaryEmployee->date)->toDateString();
            $correctWeekStart = Carbon::parse($detail->weekAnchorDate())
                ->startOfWeek(Carbon::MONDAY)
                ->toDateString();

            $pairs->push($detail->employee_id . '|' . $currentWeekStart);
            $pairs->push($detail->employee_id . '|' . $correctWeekStart);

            $detail->update(['salary_employee_id' => null]);
        }

        return $pairs->unique()->values();
    }
}
