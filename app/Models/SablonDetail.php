<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SablonDetail extends Model
{
    protected $fillable = [
        'sablon_id',
        'color_fabric_id',
        'long_fabric',
    ];

    public function sablon(): BelongsTo
    {
        return $this->belongsTo(Sablon::class, 'sablon_id');
    }

    public function colorFabric(): BelongsTo
    {
        return $this->belongsTo(ColorFabric::class, 'color_fabric_id');
    }
}
