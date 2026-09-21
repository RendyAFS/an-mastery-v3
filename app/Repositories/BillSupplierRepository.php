<?php

namespace App\Repositories;

use App\Models\BillSupplier;
use App\Models\PriceSupplier;
use App\Models\Sablon;
use App\Models\Supplier;
use Carbon\Carbon;

class BillSupplierRepository
{
    public function getBillSupplierCards(
        ?string $search = null,
        int $perPage = 12,
        ?Carbon $dateFrom = null,
        ?Carbon $dateTo = null
    ) {
        $query = Supplier::query()
            ->where('is_active', true)
            ->withCount([
                'sablons as unbilled_sablons_count' => function ($q) {
                    $q->whereDoesntHave('billSupplier');
                },
                'billSuppliers as unpaid_bills_count' => function ($q) use ($dateFrom, $dateTo) {
                    $q->where('is_paid', false);

                    if ($dateFrom && $dateTo) {
                        $q->whereBetween('date_bill', [$dateFrom, $dateTo]);
                    }
                },
            ])
            ->withSum(['billSuppliers as total_unpaid' => function ($q) use ($dateFrom, $dateTo) {
                $q->where('is_paid', false);

                if ($dateFrom && $dateTo) {
                    $q->whereBetween('date_bill', [$dateFrom, $dateTo]);
                }
            }], 'total_fee')
            ->orderBy('name');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->paginate($perPage);
    }

    public function getGroupedBySupplier(
        int $supplierId,
        ?string $search = null,
        ?Carbon $dateFrom = null,
        ?Carbon $dateTo = null
    ) {
        $query = BillSupplier::query()
            ->with(['sablon', 'sablon.imageFabric', 'sablon.typeColor', 'sablon.fabric.typeFabric', 'sablon.sablonDetails.colorFabric'])
            ->where('supplier_id', $supplierId)
            ->orderByDesc('date_bill');

        if ($search) {
            $query->where('notes', 'like', "%{$search}%");
        }

        if ($dateFrom && $dateTo) {
            $query->whereBetween('date_bill', [$dateFrom, $dateTo]);
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
                        'items' => $group->map(function ($bs) {
                            $pricePerMeter = $bs->sablon?->total_long_fabric
                                ? $bs->total_fee / $bs->sablon->total_long_fabric
                                : 0;

                            return [
                                'image_fabric'      => $bs->sablon?->imageFabric?->name ?? '-',
                                'type_color'        => $bs->sablon?->typeColor?->name ?? '-',
                                'type_fabric'       => $bs->sablon?->fabric?->typeFabric?->name ?? '-',
                                'total_fee'         => (int) $bs->total_fee,
                                'total_long_fabric' => $bs->sablon?->total_long_fabric,
                                'details'           => $bs->sablon?->sablonDetails->map(fn($d) => [
                                    'color_fabric' => $d->colorFabric?->name ?? '-',
                                    'long_fabric'  => $d->long_fabric,
                                    'total_fee'    => round($d->long_fabric * $pricePerMeter),
                                ])->values() ?? [],
                            ];
                        })->values(),
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

    public function getAvailableSablons(int $supplierId, ?string $search = null, ?string $batch = null)
    {
        $query = Sablon::query()
            ->where('supplier_id', $supplierId)
            ->where(function ($q) use ($batch) {
                $q->whereDoesntHave('billSupplier');

                if ($batch) {
                    $q->orWhereHas('billSupplier', fn($b) => $b->where('batch', $batch));
                }
            })
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
            'sablon_id'             => $sablon->id,
            'status'                => $sablon->status?->value,
            'status_label'          => $sablon->status?->labels(),
            'is_billed_in_advance'  => (bool) $sablon->is_billed_in_advance,
            'date_sablon'           => $sablon->date_sablon?->translatedFormat('d F Y'),
            'image_fabric'          => $sablon->imageFabric?->name,
            'type_fabric'           => $sablon->typeFabric?->name,
            'type_color'            => $sablon->typeColor?->name,
            'total_long_fabric'     => $sablon->total_long_fabric,
            'fabric_details'        => $sablon->relationLoaded('sablonDetails')
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
