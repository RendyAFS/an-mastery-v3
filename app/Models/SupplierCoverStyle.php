<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierCoverStyle extends Model
{
    protected $fillable = [
        'supplier_id',
        'color_from',
        'color_to',
        'icon',
        'pattern',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
