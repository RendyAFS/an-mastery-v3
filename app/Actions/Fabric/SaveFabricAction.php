<?php

namespace App\Actions\Fabric;

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
            'supplier_id'    => $data['supplier_id'],
            'code'           => $this->generateCode($supplier->name),
            'type_fabric_id' => $data['type_fabric_id'],
            'date_coming'    => $data['date_coming'],
            'seri'           => $data['seri'],
            'stock_total'    => $this->sumStock($data['fabric_details']),
            'notes'          => $data['notes'] ?? null,
        ]);

        foreach ($data['fabric_details'] as $detail) {
            $this->createDetail($fabric, $detail);
        }

        return $fabric;
    }

    private function update(Fabric $fabric, array $data): Fabric
    {
        $fabric->update([
            'supplier_id'    => $data['supplier_id'],
            'type_fabric_id' => $data['type_fabric_id'],
            'date_coming'    => $data['date_coming'],
            'seri'           => $data['seri'],
            'stock_total'    => $this->sumStock($data['fabric_details']),
            'notes'          => $data['notes'] ?? null,
        ]);

        $existingColorIds = $fabric->fabricDetails()->pluck('color_fabric_id')->all();
        $incomingColorIds  = array_column($data['fabric_details'], 'color_fabric_id');

        $fabric->fabricDetails()
            ->whereNotIn('color_fabric_id', $incomingColorIds)
            ->delete();

        foreach ($data['fabric_details'] as $detail) {
            $isNewColor = ! in_array($detail['color_fabric_id'], $existingColorIds);

            if ($isNewColor) {
                $this->createDetail($fabric, $detail);
                continue;
            }

            foreach ($data['fabric_details'] as $detail) {
                $fabricDetail = $fabric->fabricDetails()
                    ->where('color_fabric_id', $detail['color_fabric_id'])
                    ->first();

                if (! $fabricDetail) {
                    $this->createDetail($fabric, $detail);
                    continue;
                }

                $fabricDetail->update([
                    'stock' => (int) $detail['stock'],
                    'notes' => $detail['notes'] ?? null,
                ]);
            }
        }

        return $fabric;
    }

    private function createDetail(Fabric $fabric, array $detail): void
    {
        $fabric->fabricDetails()->create([
            'color_fabric_id' => $detail['color_fabric_id'],
            'stock'           => $detail['stock'],
            'notes'           => $detail['notes'] ?? null,
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
