<?php

namespace App\Repositories;

use App\Enums\StatusSablonEnum;
use App\Helpers\RupiahHelper;
use App\Models\Employee;
use App\Models\Fabric;
use App\Models\FabricDetail;
use App\Models\ImageFabric;
use App\Models\PriceEmployee;
use App\Models\Sablon;
use App\Models\Supplier;
use App\Models\TypeColor;
use App\Models\TypeFabric;

class SablonRepository
{
    public function getAll(
        string $filter = 'active',
        ?string $search = null,
        int $perPage = 12,
        ?\Carbon\Carbon $dateFrom = null,
        ?\Carbon\Carbon $dateTo = null,
        ?array $supplierIds = null
    ) {
        $query = Sablon::query()
            ->with([
                'supplier',
                'fabric.typeFabric',
                'imageFabric',
                'typeColor',
                'typeFabric',
                'priceEmployee',
                'sablonDetails.colorFabric',
                'sablonDetails.fabricDetail',
                'sablonEmployeeDetails.employee',
                'sablonEmployeeDetails.employeeChange',
            ])
            ->orderBy('date_sablon', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('notes', 'like', "%{$search}%")
                    ->orWhereHas('supplier', fn($s) => $s->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('fabric', fn($f) => $f->where('code', 'like', "%{$search}%"))
                    ->orWhereHas('imageFabric', fn($i) => $i->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('sablonEmployeeDetails.employee', fn($e) => $e->where('name', 'like', "%{$search}%"));
            });
        }

        if (!empty($supplierIds)) {
            $query->whereIn('supplier_id', $supplierIds);
        }

        if ($dateFrom && $dateTo) {
            $query->where(function ($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('date_sablon', [$dateFrom, $dateTo])
                    ->orWhere('status', StatusSablonEnum::ON_PROGRESS);
            });
        }

        return $query->paginate($perPage);
    }

    public function findWithDetails(Sablon $sablon): Sablon
    {
        return $sablon->load([
            'supplier',
            'fabric.fabricDetails.colorFabric',
            'fabric.fabricDetails.sablonDetails.sablon',
            'imageFabric',
            'typeColor',
            'typeFabric',
            'priceEmployee',
            'sablonDetails.fabricDetail',
            'sablonDetails.colorFabric',
            'sablonEmployeeDetails.fabricDetail',
            'sablonEmployeeDetails.employee',
            'sablonEmployeeDetails.employeeChange',
        ]);
    }

    /**
     * Data pendukung untuk form create/edit.
     */
    public function getFormData(?int $exceptSablonId = null): array
    {
        $priceEmployeesColl = PriceEmployee::with(['typeFabric', 'typeColor'])->orderBy('id')->get();

        return [
            'suppliers'         => Supplier::query()->where('is_active', true)->orderBy('name')->pluck('name', 'id'),
            'fabrics'           => [],
            'imageFabrics'      => ImageFabric::orderBy('name')->pluck('name', 'id'),
            'typeColors'        => TypeColor::orderBy('name')->pluck('name', 'id')->map(fn($name) => $name . ' Warna')->toArray(),
            'typeColorsRaw'     => TypeColor::orderBy('name')->get(['id', 'name'])->mapWithKeys(fn($t) => [$t->id => (float) $t->name])->toArray(),
            'typeFabrics'       => TypeFabric::orderBy('name')->pluck('name', 'id'),
            'priceEmployees'    => $priceEmployeesColl->mapWithKeys(fn($item) => [$item->id => $item->typeFabric?->name . ' - ' . RupiahHelper::format($item->price) . ' - ' . $item->typeColor?->name . ' Warna'])->toArray(),
            'priceEmployeesRaw' => $priceEmployeesColl->pluck('price', 'id')->toArray(),
            'priceEmployeeMap'  => $priceEmployeesColl->mapWithKeys(fn($p) => [$p->type_fabric_id . '_' . $p->type_color_id => $p->id])->toArray(),
            'employees'         => Employee::orderBy('name')->pluck('name', 'id'),
            'fabricDetails'     => FabricDetail::with([
                'colorFabric',
                'sablonDetails.sablon',
            ])
                ->where('stock', '>', 0)
                ->get()
                ->map(function ($d) use ($exceptSablonId) {
                    $usedStock = $d->sablonDetails
                        ->filter(function ($detail) use ($exceptSablonId) {
                            if ($exceptSablonId && $detail->sablon_id == $exceptSablonId) {
                                return false;
                            }
                            return $detail->sablon
                                && $detail->sablon->status !== StatusSablonEnum::RETURNED;
                        })
                        ->count();

                    return [
                        'id'              => $d->id,
                        'fabric_id'       => $d->fabric_id,
                        'color_fabric_id' => $d->color_fabric_id,
                        'color_name'      => $d->colorFabric->name ?? '',
                        'stock'           => max(0, $d->stock - $usedStock),
                    ];
                })
                ->filter(fn($item) => $item['stock'] > 0)
                ->values(),
        ];
    }

    public function getBySupplierAsOptions(int $supplierId, ?int $exceptSablonId = null): array
    {
        return Fabric::with([
            'fabricDetails.colorFabric',
            'fabricDetails.sablonDetails.sablon',
            'typeFabric',
        ])
            ->where('supplier_id', $supplierId)
            ->orderBy('code')
            ->get()
            ->reject(function ($fabric) use ($exceptSablonId) {
                $relevantSablonDetails = $fabric->fabricDetails
                    ->flatMap->sablonDetails
                    ->filter(function ($sd) use ($exceptSablonId) {
                        if ($exceptSablonId && $sd->sablon_id == $exceptSablonId) {
                            return false;
                        }
                        if (!$sd->sablon || $sd->sablon->deleted_at !== null) {
                            return false;
                        }
                        $status = $sd->sablon?->status;
                        $val = is_object($status) && isset($status->value) ? $status->value : $status;
                        return $val !== StatusSablonEnum::RETURNED->value && $val !== 'RETURNED';
                    });

                if ($relevantSablonDetails->isEmpty()) {
                    return false;
                }

                $allDelivered = $relevantSablonDetails->every(function ($sd) {
                    $status = $sd->sablon?->status;
                    $val = is_object($status) && isset($status->value) ? $status->value : $status;
                    return $val === StatusSablonEnum::DELIVERED->value || $val === 'DELIVERED';
                });

                $summary = $fabric->getAvailableStockSummary($exceptSablonId);

                return $allDelivered && $summary['total_pcs'] == 0;
            })
            ->mapWithKeys(function ($fabric) use ($exceptSablonId) {

                $summary = $fabric->getAvailableStockSummary($exceptSablonId);

                $excessParts = collect($summary['colors'])
                    ->filter(fn($color) => $color['excess'] > 0)
                    ->map(fn($color) => sprintf('+%d %s', $color['excess'], $color['name']))
                    ->implode(' ');

                $label = sprintf(
                    '(%d/%d Seri) - %s (%s)',
                    $summary['seri'],
                    $summary['seri_total'],
                    $fabric->typeFabric?->name ?? '-',
                    $fabric->date_coming?->translatedFormat('d F Y') ?? '-'
                );

                if ($excessParts) {
                    $label .= ' ' . $excessParts;
                }

                return [
                    $fabric->id => $label,
                ];
            })
            ->toArray();
    }

    public function canRestore(Sablon $sablon): array
    {
        if ($sablon->status === StatusSablonEnum::RETURNED) {
            return ['allowed' => true];
        }

        $fabric = $sablon->fabric;
        if (! $fabric) {
            $fabric = Fabric::withTrashed()->find($sablon->fabric_id);
            if (! $fabric || $fabric->trashed()) {
                return [
                    'allowed' => false,
                    'message' => __('sablon.restore_failed_fabric_deleted'),
                ];
            }
        }

        $fabric->loadMissing([
            'fabricDetails.colorFabric',
            'fabricDetails.sablonDetails.sablon',
            'typeFabric',
        ]);

        $neededDetails = $sablon->sablonDetails;

        if ($neededDetails->isEmpty()) {
            $summary = $fabric->getAvailableStockSummary();
            if ($summary['seri'] <= 0 && $summary['total_pcs'] <= 0) {
                return [
                    'allowed' => false,
                    'message' => __('sablon.restore_failed_stock_empty', ['fabric' => $fabric->code]),
                ];
            }
            return ['allowed' => true];
        }

        $neededPerDetail = $neededDetails->groupBy('fabric_detail_id')->map->count();

        foreach ($neededPerDetail as $fabricDetailId => $neededCount) {
            $detail = $fabric->fabricDetails->firstWhere('id', $fabricDetailId);

            if (! $detail) {
                return [
                    'allowed' => false,
                    'message' => __('sablon.restore_failed_detail_not_found'),
                ];
            }

            $used = $detail->sablonDetails
                ->filter(function ($sd) use ($sablon) {
                    if ($sd->sablon_id === $sablon->id) {
                        return false;
                    }
                    if (! $sd->sablon || $sd->sablon->deleted_at !== null) {
                        return false;
                    }
                    $status = $sd->sablon->status;
                    $val = is_object($status) && isset($status->value) ? $status->value : $status;
                    return $val !== StatusSablonEnum::RETURNED->value && $val !== 'RETURNED';
                })
                ->count();

            $available = max(0, $detail->stock - $used);

            if ($neededCount > $available) {
                $colorName = $detail->colorFabric?->name ?? '-';
                return [
                    'allowed' => false,
                    'message' => __('sablon.restore_failed_insufficient_stock', [
                        'fabric'    => $fabric->code,
                        'color'     => $colorName,
                        'available' => $available,
                        'needed'    => $neededCount,
                    ]),
                ];
            }
        }

        return ['allowed' => true];
    }
}

