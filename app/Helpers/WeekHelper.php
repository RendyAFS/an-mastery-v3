<?php

namespace App\Helpers;

use Carbon\Carbon;

class WeekHelper
{
    public static function parseWeekBoundary(?string $week, bool $isEnd): ?Carbon
    {
        if (!$week || !preg_match('/^(\d{4})-W(\d{2})$/', $week, $matches)) {
            return null;
        }

        $year       = (int) $matches[1];
        $weekNumber = (int) $matches[2];

        $date = Carbon::now()->setISODate($year, $weekNumber);

        return $isEnd
            ? $date->endOfWeek(Carbon::SUNDAY)
            : $date->startOfWeek(Carbon::MONDAY);
    }

    public static function parseRange(?string $weekStart, ?string $weekEnd): array
    {
        return [
            static::parseWeekBoundary($weekStart, false),
            static::parseWeekBoundary($weekEnd, true),
        ];
    }
}
