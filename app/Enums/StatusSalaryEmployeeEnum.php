<?php

namespace App\Enums;

enum StatusSalaryEmployeeEnum: string
{
    case PENDING = 'PENDING';
    case DONE = 'DONE';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::DONE    => 'Done',
        };
    }
}
