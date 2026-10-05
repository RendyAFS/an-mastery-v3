<?php

namespace App\Actions\Sablon;

use App\Models\PriceEmployee;
use App\Models\Sablon;
use App\Models\TypeColor;

class CalculateSablonAction
{
    public const TOLERANCE = 1;

    public function handle(array $data, ?Sablon $sablon = null): array
    {
        $fabricDetails   = $data['fabric_details'] ?? [];
        $employeeDetails = $data['employee_details'] ?? [];

        $price      = (float) (PriceEmployee::query()->whereKey($data['price_employee_id'] ?? null)->value('price') ?? 0);
        $colorCount = (float) (TypeColor::query()->whereKey($data['type_color_id'] ?? null)->value('name') ?? 0);

        $totalLongFabric = $this->totalLongFabric($fabricDetails);
        $totalSablon     = $this->computeTotalSablon($totalLongFabric, $price);
        $ratePerLayer    = $this->computeRatePerLayer($totalSablon, $colorCount);

        $lockedFees   = $this->lockedFees($sablon);
        $employeeFees = [];

        foreach ($employeeDetails as $index => $detail) {
            $id = isset($detail['id']) ? (int) $detail['id'] : null;

            if ($id && array_key_exists($id, $lockedFees)) {
                $employeeFees[$index] = ['fee' => $lockedFees[$id], 'locked' => true];
                continue;
            }

            $employeeFees[$index] = [
                'fee'    => $this->computeFee($ratePerLayer, $detail['layers'] ?? 0),
                'locked' => false,
            ];
        }

        return [
            'total_long_fabric' => $totalLongFabric,
            'total_sablon'      => $totalSablon,
            'rate_per_layer'    => $ratePerLayer,
            'employee_fees'     => $employeeFees,
        ];
    }

    public function totalLongFabric(array $fabricDetails): float
    {
        return (float) collect($fabricDetails)->sum(fn($row) => (float) ($row['long_fabric'] ?? 0));
    }

    public function computeTotalSablon(float $totalLong, float $price): float
    {
        return $price ? $totalLong * $price : 0.0;
    }

    public function computeRatePerLayer(float $totalSablon, float $colorCount): float
    {
        return $colorCount ? $totalSablon / $colorCount : 0.0;
    }

    public function computeFee(float $ratePerLayer, mixed $layers): float
    {
        return $ratePerLayer * (float) ($layers ?: 0);
    }

    public function isMismatch(mixed $clientValue, float $serverValue): bool
    {
        return abs((float) ($clientValue ?? 0) - $serverValue) > self::TOLERANCE;
    }

    private function lockedFees(?Sablon $sablon): array
    {
        if (! $sablon?->exists) {
            return [];
        }

        return $sablon->sablonEmployeeDetails()
            ->where(function ($q) {
                $q->where('is_settled', true)
                    ->orWhereNotNull('settlement_of_id')
                    ->orWhere(function ($q2) {
                        $q2->where('is_paid', true)->where('is_bon', false);
                    });
            })
            ->pluck('fee', 'id')
            ->map(fn($fee) => (float) $fee)
            ->all();
    }
}
