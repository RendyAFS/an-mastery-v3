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
        return __('enum.status_sablon.' . $this->value);
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $case) => [$case->value => $case->labels()])
            ->toArray();
    }
}
