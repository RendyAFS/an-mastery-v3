<?php

namespace App\Actions\SalaryEmployee;

use App\Enums\StatusSalaryEmployeeEnum;
use App\Models\Memo;
use App\Models\Presence;
use App\Models\SablonEmployeeDetail;
use App\Models\SalaryEmployee;
use Carbon\Carbon;
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

            if ($wasPaid && $hasNewData && $requestedStatus !== StatusSalaryEmployeeEnum::PAID) {
                $salary->status = StatusSalaryEmployeeEnum::PENDING;
            } elseif ($requestedStatus !== null) {
                $salary->status = $requestedStatus;
            }

            if ($additionalFee !== null) {
                $normalized = array_map(fn($af) => [
                    'nominal' => (float) ($af['nominal'] ?? 0),
                    'notes'   => (string) ($af['notes'] ?? ''),
                ], $additionalFee);

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

                $this->cascadePreviousPendingToPaid($salary);
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
}
