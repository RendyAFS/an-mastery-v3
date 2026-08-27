<?php

namespace App\Models;

use App\Enums\StatusSablonEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Fabric extends Model
{
    use Userstamps, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'type_fabric_id',
        'date_coming',
        'code',
        'seri',
        'stock_total',
        'notes',
    ];

    protected $casts = [
        'date_coming' => 'date',
        'stock_total' => 'integer',
    ];

    public function getAvailableStockSummaryAttribute(): array
    {
        $details = $this->fabricDetails;

        if ($details->isEmpty()) {
            return [
                'total_pcs'   => 0,
                'seri'        => 0,
                'type_fabric' => $this->typeFabric?->name,
                'date_coming' => $this->date_coming?->format('Y-m-d'),
                'colors'      => [],
            ];
        }

        $colorsData = $details->map(function ($detail) {
            if ($detail->relationLoaded('sablonDetails')) {
                $used = $detail->sablonDetails->filter(function ($sd) {
                    $sablonStatus = $sd->sablon?->status;
                    $val = is_object($sablonStatus) && isset($sablonStatus->value) ? $sablonStatus->value : $sablonStatus;
                    return $val !== StatusSablonEnum::RETURNED->value && $val !== 'RETURNED';
                })->count();
            } else {
                $used = $detail->sablonDetails()
                    ->whereHas('sablon', function ($q) {
                        $q->where('status', '!=', 'RETURNED');
                    })
                    ->count();
            }

            $available = max($detail->stock - $used, 0);

            return [
                'name'      => $detail->colorFabric?->name,
                'color'     => $detail->colorFabric?->code_color,
                'available' => $available,
            ];
        });

        $stocks   = $colorsData->pluck('available');
        $seri     = $stocks->min() ?? 0;
        $totalPcs = $stocks->sum();

        $colors = $details->map(function ($detail, $index) use ($colorsData, $seri) {
            $available = $colorsData[$index]['available'];

            return [
                'name'   => $detail->colorFabric?->name,
                'color'  => $detail->colorFabric?->code_color,
                'stock'  => $available,
                'excess' => max($available - $seri, 0),
            ];
        });

        return [
            'total_pcs'   => $totalPcs,
            'seri'        => $seri,
            'type_fabric' => $this->typeFabric?->name,
            'date_coming' => $this->date_coming?->format('Y-m-d'),
            'colors'      => $colors,
        ];
    }

    public function getTotalInventoryFabricAttribute(): array
    {
        $details = $this->fabricDetails;

        if ($details->isEmpty()) {
            return [
                'total_pcs'   => 0,
                'seri'        => 0,
                'type_fabric' => $this->typeFabric?->name,
                'date_coming' => $this->date_coming?->format('Y-m-d'),
                'colors'      => [],
            ];
        }

        $seri = $details->min('stock');

        $colors = $details->map(function ($detail) use ($seri) {
            if ($detail->relationLoaded('sablonDetails')) {
                $grouped = $detail->sablonDetails->groupBy(function ($sd) {
                    $status = $sd->sablon?->status;
                    return is_object($status) && isset($status->value) ? $status->value : (string) $status;
                })->map->count();
            } else {
                $grouped = $detail->sablonDetails()
                    ->join('sablons', 'sablon_details.sablon_id', '=', 'sablons.id')
                    ->selectRaw('sablons.status, count(*) as total')
                    ->groupBy('sablons.status')
                    ->pluck('total', 'status');
            }

            $statuses = collect(StatusSablonEnum::cases())->map(function (StatusSablonEnum $status) use ($grouped) {
                $count = (int) ($grouped[$status->value] ?? 0);

                return [
                    'status' => $status->value,
                    'label'  => $status->labels(),
                    'count'  => $count,
                ];
            });

            return [
                'name'     => $detail->colorFabric?->name,
                'color'    => $detail->colorFabric?->code_color,
                'stock'    => $detail->stock,
                'excess'   => max($detail->stock - $seri, 0),
                'statuses' => $statuses,
            ];
        });

        $totalPcs = $details->sum('stock');

        return [
            'total_pcs'   => $totalPcs,
            'seri'        => $seri,
            'type_fabric' => $this->typeFabric?->name,
            'date_coming' => $this->date_coming?->format('Y-m-d'),
            'colors'      => $colors,
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function typeFabric(): BelongsTo
    {
        return $this->belongsTo(TypeFabric::class, 'type_fabric_id');
    }

    public function fabricDetails(): HasMany
    {
        return $this->hasMany(FabricDetail::class, 'fabric_id');
    }
}
