<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SablonDetail extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'sablon_id',
        'fabric_detail_id',
        'color_fabric_id',
        'long_fabric',
    ];

    public function sablon(): BelongsTo
    {
        return $this->belongsTo(Sablon::class, 'sablon_id');
    }

    public function fabricDetail(): BelongsTo
    {
        return $this->belongsTo(FabricDetail::class, 'fabric_detail_id');
    }

    public function colorFabric(): BelongsTo
    {
        return $this->belongsTo(ColorFabric::class, 'color_fabric_id');
    }
}
