<?php

namespace App\Enums;

enum StatusHistoryStockEnum: string
{
    case IN         = 'IN';
    case ADJUSTMENT = 'ADJUSTMENT';
    case OUT        = 'OUT';

    public function labels(): string
    {
        return match ($this) {
            self::IN         => 'In',
            self::ADJUSTMENT => 'Adjustment',
            self::OUT        => 'Out',
        };
    }
}
