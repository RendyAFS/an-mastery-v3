<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mattiverse\Userstamps\Traits\Userstamps;

class SupplierCoverStyle extends Model
{
    use Userstamps;

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
