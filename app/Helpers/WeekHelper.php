<?php

namespace App\Helpers;

use Carbon\Carbon;

class WeekHelper
{
    public static function parseRange(?string $dateFrom, ?string $dateTo): array
    {
        return [
            $dateFrom ? Carbon::parse($dateFrom)->startOfDay() : null,
            $dateTo ? Carbon::parse($dateTo)->endOfDay() : null,
        ];
    }
}
