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
            ->with(['sablon', 'sablon.imageFabric', 'sablon.typeColor', 'sablon.fabric.typeFabric'])
            ->where('supplier_id', $supplierId)
            ->orderByDesc('date_bill');

        if ($search) {
            $query->where('notes', 'like', "%{$search}%");
        }

        $bills = $query->get();

        $byWeek = $bills->groupBy(function ($bill) {
            return $bill->date_bill
                ? Carbon::parse($bill->date_bill)->startOfWeek()->format('Y-m-d')
                : 'no-date';
        });

        return $byWeek
            ->map(function ($items, $weekStart) {
                $weekLabel = $weekStart !== 'no-date'
                    ? Carbon::parse($weekStart)->translatedFormat('d F Y') . ' - ' . Carbon::parse($weekStart)->endOfWeek()->translatedFormat('d F Y')
                    : 'Tanpa Tanggal';

                $batches = $items->groupBy('batch')->map(function ($group) {
                    $first = $group->first();

                    return [
                        'batch'      => $first->batch,
                        'date_bill'  => $first->date_bill?->format('Y-m-d'),
                        'is_paid'    => (bool) $first->is_paid,
                        'notes'      => $first->notes,
                        'count'      => $group->count(),
                        'total_fee'  => (int) $group->sum('total_fee'),
                        // rincian per sablon dalam batch ini
                        'items'      => $group->map(fn($bs) => [
                            'image_fabric'      => $bs->sablon?->imageFabric?->name ?? '-',
                            'type_color'        => $bs->sablon?->typeColor?->name ?? '-',
                            'type_fabric'       => $bs->sablon?->fabric?->typeFabric?->name ?? '-',
                            'total_fee'         => (int) $bs->total_fee,
                            'total_long_fabric' => $bs->sablon?->total_long_fabric,
                        ])->values(),
                        // batch dianggap "deleted" hanya jika seluruh row-nya sudah soft-deleted
                        'deleted_at' => $group->every(fn($b) => $b->deleted_at !== null)
                            ? $first->deleted_at?->format('Y-m-d H:i:s')
                            : null,
                    ];
                })->values();

                return [
                    'week_start'   => $weekStart,
                    'week_label'   => $weekLabel,
                    'unpaid'       => $batches->where('is_paid', false)->values(),
                    'paid'         => $batches->where('is_paid', true)->values(),
                    'total_unpaid' => (int) $batches->where('is_paid', false)->sum('total_fee'),
                    'total_paid'   => (int) $batches->where('is_paid', true)->sum('total_fee'),
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
            ->with(['imageFabric', 'typeFabric', 'typeColor', 'sablonDetails.colorFabric'])
            ->orderByDesc('date_sablon');

        if ($search) {
            $query->whereHas('imageFabric', fn($q) => $q->where('name', 'like', "%{$search}%"));
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
            'status'            => $sablon->status?->value,
            'status_label'      => $sablon->status?->labels(),
            'date_sablon'       => $sablon->date_sablon?->translatedFormat('d F Y'),
            'image_fabric'      => $sablon->imageFabric?->name,
            'type_fabric'       => $sablon->typeFabric?->name,
            'type_color'        => $sablon->typeColor?->name,
            'total_long_fabric' => $sablon->total_long_fabric,
            'fabric_details'    => $sablon->relationLoaded('sablonDetails')
                ? $sablon->sablonDetails->map(fn($d) => [
                    'color_fabric' => $d->colorFabric?->name,
                    'long_fabric'  => $d->long_fabric,
                ])->values()
                : [],
            'price_supplier_id' => $priceSupplier->id ?? null,
            'price'             => $price,
            'total_fee'         => $totalFee,
        ];
    }
}
