<?php

namespace App\Actions\SalaryEmployee;

use App\Models\SalaryEmployee;

class UpdateAdditionalFeeAction
{
    public function handle(SalaryEmployee $salaryEmployee, array $additionalFee): SalaryEmployee
    {
        $clean = array_values(
            array_map(fn($af) => [
                'nominal' => (float) ($af['nominal'] ?? 0),
                'notes'   => (string) ($af['notes'] ?? ''),
            ], $additionalFee)
        );

        $salaryEmployee->update(['additional_fee' => $clean]);

        return $salaryEmployee->fresh();
    }
}
