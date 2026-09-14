<?php

namespace App\Helpers;

class SalaryBonusHelper
{
    /**
     * Tiers configuration for automatic bonus based on total sablon fee.
     * Ordered from highest threshold to lowest.
     */
    public const TIERS = [
        ['min' => 500000, 'bonus' => 25000],
        ['min' => 400000, 'bonus' => 20000],
        ['min' => 250000, 'bonus' => 15000],
        ['min' => 200000, 'bonus' => 10000],
    ];

    /**
     * Get tiers configuration.
     *
     * @return array<int, array{min: int, bonus: int}>
     */
    public static function getTiers(): array
    {
        return self::TIERS;
    }

    /**
     * Get unique bonus amounts sorted in ascending order (e.g. for quick action buttons).
     *
     * @return array<int, int>
     */
    public static function getBonusAmounts(): array
    {
        $amounts = array_values(array_unique(array_column(self::TIERS, 'bonus')));
        sort($amounts);

        return $amounts;
    }

    /**
     * Calculate automatic bonus amount based on total sablon fee.
     *
     * Rp 200.000 - Rp 249.999 : 10.000
     * Rp 250.000 - Rp 399.999 : 15.000
     * Rp 400.000 - Rp 499.999 : 20.000
     * Rp 500.000+             : 25.000
     */
    public static function calculateBonus(float|int $totalSablon): int
    {
        $amount = (float) $totalSablon;

        foreach (self::TIERS as $tier) {
            if ($amount >= $tier['min']) {
                return $tier['bonus'];
            }
        }

        return 0;
    }
}
