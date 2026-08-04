<?php

namespace App\Enums;

enum StatusSalaryEmployeeEnum: string
{
    case PENDING = 'PENDING';
    case PAID    = 'PAID';

    public function labels(): string
    {
        return __('enum.status_salary_employee.' . $this->value);
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $case) => [$case->value => $case->labels()])
            ->toArray();
    }
}
