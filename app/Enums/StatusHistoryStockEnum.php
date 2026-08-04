<?php

namespace App\Enums;

enum StatusHistoryStockEnum: string
{
    case IN         = 'IN';
    case ADJUSTMENT = 'ADJUSTMENT';
    case OUT        = 'OUT';

    public function labels(): string
    {
        return __('enum.status_history_stock.' . $this->value);
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $case) => [$case->value => $case->labels()])
            ->toArray();
    }
}
