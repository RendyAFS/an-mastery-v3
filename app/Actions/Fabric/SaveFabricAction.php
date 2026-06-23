<?php

namespace App\Actions\Fabric;

use App\Enums\StatusHistoryStockEnum;
use App\Models\Fabric;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class SaveFabricAction
{
    public function execute(array $data, ?Fabric $fabric = null): Fabric
    {
        return DB::transaction(function () use ($data, $fabric) {
            return $fabric
                ? $this->update($fabric, $data)
                : $this->store($data);
        });
    }

    private function store(array $data): Fabric
    {
        $supplier = Supplier::findOrFail($data['supplier_id']);

        $fabric = Fabric::create([
            'supplier_id' => $data['supplier_id'],
            'code'        => $this->generateCode($supplier->name),
            'seri'        => $data['seri'],
            'stock_total' => $this->sumStock($data['fabric_details']),
            'notes'       => $data['notes'] ?? null,
        ]);

        foreach ($data['fabric_details'] as $detail) {
            $this->createDetailWithHistory($fabric, $detail);
        }

        return $fabric;
    }

    private function update(Fabric $fabric, array $data): Fabric
    {
        $fabric->update([
            'supplier_id' => $data['supplier_id'],
            'seri'        => $data['seri'],
            'stock_total' => $this->sumStock($data['fabric_details']),
            'notes'       => $data['notes'] ?? null,
        ]);

        $existingColorIds = $fabric->fabricDetails()->pluck('color_fabric_id')->all();
        $incomingColorIds  = array_column($data['fabric_details'], 'color_fabric_id');

        $fabric->fabricDetails()
            ->whereNotIn('color_fabric_id', $incomingColorIds)
            ->delete();

        foreach ($data['fabric_details'] as $detail) {
            $isNewColor = ! in_array($detail['color_fabric_id'], $existingColorIds);

            if ($isNewColor) {
                $this->createDetailWithHistory($fabric, $detail);
                continue;
            }

            foreach ($data['fabric_details'] as $detail) {
                $fabricDetail = $fabric->fabricDetails()
                    ->where('color_fabric_id', $detail['color_fabric_id'])
                    ->first();

                if (! $fabricDetail) {
                    $this->createDetailWithHistory($fabric, $detail);
                    continue;
                }

                $oldStock = $fabricDetail->stock;
                $newStock = (int) $detail['stock'];

                if ($oldStock !== $newStock) {
                    $diff = $newStock - $oldStock;

                    $fabricDetail->historyStocks()->create([
                        'status' => StatusHistoryStockEnum::ADJUSTMENT,
                        'total'  => $diff,
                        'notes'  => $detail['notes'] ?? null,
                    ]);
                }

                $fabricDetail->update([
                    'stock' => $newStock,
                    'notes' => $detail['notes'] ?? null,
                ]);
            }
        }

        return $fabric;
    }

    private function createDetailWithHistory(Fabric $fabric, array $detail): void
    {
        $fabricDetail = $fabric->fabricDetails()->create([
            'color_fabric_id' => $detail['color_fabric_id'],
            'stock'           => $detail['stock'],
            'notes'           => $detail['notes'] ?? null,
        ]);

        $fabricDetail->historyStocks()->create([
            'status' => StatusHistoryStockEnum::IN,
            'total'  => $detail['stock'],
            'notes'  => $detail['notes'] ?? null,
        ]);
    }

    private function generateCode(string $supplierName): string
    {
        return strtoupper($supplierName . '-' . now()->format('YmdHis'));
    }

    private function sumStock(array $details): int
    {
        return (int) array_sum(array_column($details, 'stock'));
    }
}
