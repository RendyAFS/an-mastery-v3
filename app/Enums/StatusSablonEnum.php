<?php

namespace App\Enums;

enum StatusSablonEnum: string
{
    case ON_PROGRESS = 'ON_PROGRESS';
    case DONE        = 'DONE';
    case DELIVERED   = 'DELIVERED';
    case RETURNED    = 'RETURNED';

    public function labels(): string
    {
        return match ($this) {
            self::ON_PROGRESS => 'On Progress',
            self::DONE        => 'Done',
            self::DELIVERED   => 'Delivered',
            self::RETURNED    => 'Returned',
        };
    }
}
