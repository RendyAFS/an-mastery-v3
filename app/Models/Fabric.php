<?php

namespace App\Models;

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

        $stocks = $details->map(function ($detail) {

            $used = $detail->sablonDetails()
                ->whereHas('sablon', function ($q) {
                    $q->where('status', '!=', 'RETURNED');
                })
                ->count();

            return max($detail->stock - $used, 0);
        });

        $seri = $stocks->min();
        $totalPcs = $stocks->sum();

        $colors = $details->map(function ($detail) {

            $used = $detail->sablonDetails()
                ->whereHas('sablon', function ($q) {
                    $q->where('status', '!=', 'RETURNED');
                })
                ->count();

            $available = max($detail->stock - $used, 0);

            return [
                'name'  => $detail->colorFabric?->name,
                'color' => $detail->colorFabric?->code_color,
                'stock' => $available,
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
