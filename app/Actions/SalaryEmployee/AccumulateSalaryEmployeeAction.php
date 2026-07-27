<?php

namespace App\Actions\SalaryEmployee;

use App\Enums\StatusSalaryEmployeeEnum;
use App\Models\SablonEmployeeDetail;
use App\Models\SalaryEmployee;

class AccumulateSalaryEmployeeAction
{
    public function handle(SablonEmployeeDetail $detail): SalaryEmployee
    {
        $salary = SalaryEmployee::where('employee_id', $detail->employee_id)
            ->where('status', StatusSalaryEmployeeEnum::PENDING)
            ->latest('id')
            ->first();

        if (! $salary) {
            $salary = SalaryEmployee::create([
                'employee_id'    => $detail->employee_id,
                'fee'            => 0,
                'additional_fee' => [],
                'status'         => StatusSalaryEmployeeEnum::PENDING,
                'date'           => now()->toDateString(),
                'notes'          => null,
            ]);
        }

        $detail->salary_employee_id = $salary->id;
        $detail->saveQuietly();

        $this->recalculate($salary);

        return $salary->fresh();
    }

    public function recalculate(SalaryEmployee $salary): void
    {
        $total = $salary->sablonEmployeeDetails()
            ->get()
            ->sum(
                fn(SablonEmployeeDetail $d) => (float) $d->fee
                    + collect($d->additional_fee ?? [])->sum(fn($af) => (float) ($af['nominal'] ?? 0))
            );

        $salary->update(['fee' => $total]);
    }
}
