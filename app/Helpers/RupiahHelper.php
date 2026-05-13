<?php

namespace App\Helpers;

class RupiahHelper
{
    public static function format(
        int|float|string|null $rupiah_number,
        string $prefix = 'Rp'
    ): string {
        if ($rupiah_number === null || $rupiah_number === '') {
            return $prefix . '0';
        }

        return $prefix . number_format((float) $rupiah_number, 0, ',', '.');
    }

    public static function number(
        int|float|string|null $rupiah_number
    ): string {
        if ($rupiah_number === null || $rupiah_number === '') {
            return '0';
        }

        return number_format((float) $rupiah_number, 0, ',', '.');
    }

    public static function income(int|float|null $value): string
    {
        if (! $value) {
            return '0';
        }

        if ($value >= 1_000_000_000) {
            $miliar = $value / 1_000_000_000;

            return $miliar % 1 === 0
                ? number_format($miliar, 0, ',', '.') . ' M'
                : number_format($miliar, 2, ',', '.') . ' M';
        }

        if ($value >= 1_000_000) {
            return number_format($value, 0, ',', '.') . ' Jt';
        }

        return number_format($value, 0, ',', '.');
    }
}
