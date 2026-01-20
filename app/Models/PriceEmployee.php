<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class PriceEmployee extends Model
{
    use Userstamps, SoftDeletes;

    protected $fillable = [
        'type_fabric_id',
        'type_color_id',
        'price',
        'notes',
    ];

    public function typeFabric(): BelongsTo
    {
        return $this->belongsTo(TypeFabric::class, 'type_fabric_id');
    }

    public function typeColor(): BelongsTo
    {
        return $this->belongsTo(TypeColor::class, 'type_color_id');
    }
}
