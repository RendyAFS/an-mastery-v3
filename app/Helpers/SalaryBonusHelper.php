<?php

namespace App\Helpers;

use App\Models\Bonus;

class SalaryBonusHelper
{
    /**
     * Default tiers fallback if database table is empty or unpopulated.
     */
    public const DEFAULT_TIERS = [
        ['min' => 500000, 'bonus' => 25000],
        ['min' => 400000, 'bonus' => 20000],
        ['min' => 250000, 'bonus' => 15000],
        ['min' => 200000, 'bonus' => 10000],
    ];

    /**
     * Get tiers configuration from database, or fallback if empty.
     * Ordered from highest threshold to lowest.
     *
     * @return array<int, array{min: int, bonus: int}>
     */
    public static function getTiers(): array
    {
        try {
            $tiers = Bonus::query()
                ->orderBy('min', 'desc')
                ->get(['min', 'bonus'])
                ->map(fn($b) => [
                    'min'   => (int) $b->min,
                    'bonus' => (int) $b->bonus,
                ])
                ->toArray();

            if (!empty($tiers)) {
                return $tiers;
            }
        } catch (\Throwable $e) {
            // Fallback if table does not exist or database connection issue
        }

        return self::DEFAULT_TIERS;
    }

    /**
     * Get unique bonus amounts sorted in ascending order (e.g. for quick action buttons).
     *
     * @return array<int, int>
     */
    public static function getBonusAmounts(): array
    {
        $tiers = static::getTiers();
        $amounts = array_values(array_unique(array_column($tiers, 'bonus')));
        sort($amounts);

        return $amounts;
    }

    /**
     * Calculate automatic bonus amount based on total sablon fee.
     */
    public static function calculateBonus(float|int $totalSablon): int
    {
        $amount = (float) $totalSablon;

        foreach (static::getTiers() as $tier) {
            if ($amount >= $tier['min']) {
                return (int) $tier['bonus'];
            }
        }

        return 0;
    }
}
