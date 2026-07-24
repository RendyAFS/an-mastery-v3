<?php

namespace App\Repositories;

use App\Models\BillSupplier;
use App\Models\PriceSupplier;
use App\Models\Sablon;
use Carbon\Carbon;

class BillSupplierRepository
{
    public function getGroupedBySupplier(int $supplierId, ?string $search = null)
    {
        $query = BillSupplier::query()
            ->withTrashed()
            ->with([
                'sablon.fabric',
                'sablon.typeFabric',
                'sablon.typeColor',
                'priceSupplier',
                'details.sablonDetail.fabricDetail.fabric',
                'details.sablonDetail.colorFabric',
            ])
            ->where('supplier_id', $supplierId)
            ->orderByDesc('date_bill');

        if ($search) {
            $query->where('notes', 'like', "%{$search}%");
        }

        $bills = $query->get();

        $grouped = $bills->groupBy(function ($bill) {
            return $bill->date_bill
                ? Carbon::parse($bill->date_bill)->startOfWeek()->format('Y-m-d')
                : 'no-date';
        });

        return $grouped
            ->map(function ($items, $weekStart) {
                $weekLabel = $weekStart !== 'no-date'
                    ? Carbon::parse($weekStart)->translatedFormat('d F Y') . ' - ' . Carbon::parse($weekStart)->endOfWeek()->translatedFormat('d F Y')
                    : 'Tanpa Tanggal';

                return [
                    'week_start'   => $weekStart,
                    'week_label'   => $weekLabel,
                    'unpaid'       => $items->where('is_paid', false)->values(),
                    'paid'         => $items->where('is_paid', true)->values(),
                    'total_unpaid' => (int) $items->where('is_paid', false)->sum('total_fee'),
                    'total_paid'   => (int) $items->where('is_paid', true)->sum('total_fee'),
                ];
            })
            ->sortKeysDesc()
            ->values();
    }

    public function getAvailableSablons(int $supplierId, ?string $search = null)
    {
        $query = Sablon::query()
            ->where('supplier_id', $supplierId)
            ->whereDoesntHave('billSupplier')
            ->with(['fabric', 'typeFabric', 'typeColor'])
            ->orderByDesc('date_sablon');

        if ($search) {
            $query->whereHas('fabric', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        return $query->get();
    }

    public function calculatePreview(Sablon $sablon): array
    {
        $priceSupplier = PriceSupplier::query()
            ->where('supplier_id', $sablon->supplier_id)
            ->where('type_fabric_id', $sablon->type_fabric_id)
            ->where('type_color_id', $sablon->type_color_id)
            ->first();

        $price    = $priceSupplier->price ?? 0;
        $totalFee = $sablon->total_long_fabric * $price;

        return [
            'sablon_id'         => $sablon->id,
            'total_long_fabric' => $sablon->total_long_fabric,
            'price_supplier_id' => $priceSupplier->id ?? null,
            'price'             => $price,
            'total_fee'         => $totalFee,
        ];
    }
}
