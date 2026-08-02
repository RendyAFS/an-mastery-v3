<?php

namespace App\Actions\SalaryEmployee;

use App\Enums\StatusSalaryEmployeeEnum;
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
        ?string $notes = null
    ): SalaryEmployee {
        return DB::transaction(function () use ($employeeId, $weekOf, $status, $additionalFee, $notes) {
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

            $alreadyLinkedDetails = $salary->exists
                ? SablonEmployeeDetail::query()
                ->where('salary_employee_id', $salary->id)
                ->get()
                : collect();

            $newEligibleDetails = SablonEmployeeDetail::query()
                ->where('employee_id', $employeeId)
                ->whereNull('salary_employee_id')
                ->eligibleForSalary()
                ->whereHas('sablon', fn($q) => $q->whereBetween('date_sablon', [$start, $end]))
                ->get();

            $eligibleDetails = $alreadyLinkedDetails->concat($newEligibleDetails);
            $totalFee = $eligibleDetails->sum(fn(SablonEmployeeDetail $d) => (float) $d->fee);
            $presenceTotal = (float) (Presence::where('employee_id', $employeeId)
                ->where('week_of', $start)
                ->value('total') ?? 0);

            if ($status !== null) {
                $salary->status = StatusSalaryEmployeeEnum::from($status);
            }

            if ($additionalFee !== null) {
                $salary->additional_fee = array_values(
                    array_map(fn($af) => [
                        'nominal' => (float) ($af['nominal'] ?? 0),
                        'notes'   => (string) ($af['notes'] ?? ''),
                    ], $additionalFee)
                );
            }

            if ($notes !== null) {
                $salary->notes = $notes;
            }

            $additionalFeeTotal = collect($salary->additional_fee ?? [])
                ->sum(fn($af) => (float) ($af['nominal'] ?? 0));

            $salary->fee = $totalFee + $presenceTotal + $additionalFeeTotal;

            $salary->save();

            SablonEmployeeDetail::whereIn('id', $eligibleDetails->pluck('id'))
                ->update(['salary_employee_id' => $salary->id]);

            if ($salary->status === StatusSalaryEmployeeEnum::PAID) {
                SablonEmployeeDetail::where('salary_employee_id', $salary->id)
                    ->update(['is_paid' => true]);
            } else {
                SablonEmployeeDetail::where('salary_employee_id', $salary->id)
                    ->update(['is_paid' => false]);
            }

            return $salary->fresh([
                'employee',
                'sablonEmployeeDetails.sablon.supplier',
                'sablonEmployeeDetails.sablon.imageFabric',
            ]);
        });
    }

    public function handleBulk(Carbon $dateFrom, Carbon $dateTo): int
    {
        $start = $dateFrom->copy()->startOfWeek(Carbon::MONDAY);
        $end   = $dateTo->copy()->endOfWeek(Carbon::SUNDAY);

        $pairs = SablonEmployeeDetail::query()
            ->whereNull('salary_employee_id')
            ->eligibleForSalary()
            ->whereHas('sablon', fn($q) => $q->whereBetween('date_sablon', [$start->toDateString(), $end->toDateString()]))
            ->with('sablon')
            ->get()
            ->map(function (SablonEmployeeDetail $detail) {
                $weekStart = Carbon::parse($detail->sablon->date_sablon)
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
}
