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
        'code',
        'seri',
        'stock_total',
        'notes',
    ];

    protected $casts = [
        'stock_total' => 'integer',
    ];

    public function getStockSummaryAttribute(): array
    {
        $details = $this->fabricDetails;

        if ($details->isEmpty()) {
            return [
                'total_pcs' => 0,
                'seri' => 0,
                'colors' => []
            ];
        }

        $stocks = $details->pluck('stock');

        $seri = $stocks->min();
        $totalPcs = $stocks->sum();

        $colors = $details->map(function ($detail) use ($seri) {
            return [
                'name'   => $detail->colorFabric?->name,
                'color'  => $detail->colorFabric?->code_color,
                'stock'  => $detail->stock,
            ];
        });

        return [
            'total_pcs' => $totalPcs,
            'seri'      => $seri,
            'colors'    => $colors,
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function fabricDetails(): HasMany
    {
        return $this->hasMany(FabricDetail::class, 'fabric_id');
    }
}
