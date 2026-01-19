<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FabricDetail extends Model
{
    protected $fillable = [
        'fabric_id',
        'color_fabric_id',
        'stock',
        'notes',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    public function fabric(): BelongsTo
    {
        return $this->belongsTo(Fabric::class, 'fabric_id');
    }

    public function colorFabric(): BelongsTo
    {
        return $this->belongsTo(ColorFabric::class, 'color_fabric_id');
    }
}
