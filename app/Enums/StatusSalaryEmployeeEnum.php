<?php

namespace App\Enums;

enum StatusSalaryEmployeeEnum: string
{
    case PENDING = 'PENDING';
    case PAID    = 'PAID';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::PAID    => 'Paid',
        };
    }
}
