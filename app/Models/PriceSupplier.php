<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class PriceSupplier extends Model
{
    use Userstamps;
    use SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'type_fabric_id',
        'type_color_id',
        'price',
        'notes',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function typeFabric(): BelongsTo
    {
        return $this->belongsTo(TypeFabric::class, 'type_fabric_id');
    }

    public function typeColor(): BelongsTo
    {
        return $this->belongsTo(TypeColor::class, 'type_color_id');
    }
}
