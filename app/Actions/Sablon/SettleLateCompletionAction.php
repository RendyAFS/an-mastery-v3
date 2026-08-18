<?php

namespace App\Actions\Sablon;

use App\Enums\StatusSablonEnum;
use App\Enums\StatusSalaryEmployeeEnum;
use App\Models\Sablon;
use App\Models\SalaryEmployee;
use Carbon\Carbon;

class SettleLateCompletionAction
{
    public function handle(Sablon $sablon): int
    {
        if (! in_array($sablon->status, [
            StatusSablonEnum::DONE,
            StatusSablonEnum::DELIVERED,
        ])) {
            return 0;
        }

        $details = $sablon->sablonEmployeeDetails()
            ->where('is_bon', false)
            ->where('is_settled', false)
            ->where(function ($q) {
                $q->where('is_paid', false)->orWhereNull('is_paid');
            })
            ->whereNull('settlement_of_id')
            ->whereNull('late_eligible_at')
            ->get();

        if ($details->isEmpty()) {
            return 0;
        }

        $weekStart = Carbon::parse($sablon->date_sablon)
            ->startOfWeek(Carbon::MONDAY)
            ->toDateString();

        $count = 0;

        foreach ($details as $detail) {
            $originalSalary = SalaryEmployee::query()
                ->where('employee_id', $detail->employee_id)
                ->where('date', $weekStart)
                ->first();

            if (! $originalSalary || $originalSalary->status !== StatusSalaryEmployeeEnum::PAID) {
                continue;
            }

            $detail->update(['late_eligible_at' => now()->toDateString()]);
            $count++;
        }

        return $count;
    }
}
