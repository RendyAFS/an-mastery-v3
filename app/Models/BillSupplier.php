<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class BillSupplier extends Model
{
    use Userstamps, SoftDeletes;

    protected $fillable = [
        'batch',
        'supplier_id',
        'price_supplier_id',
        'sablon_id',
        'total_fee',
        'date_bill',
        'is_paid',
        'notes',
    ];

    protected $casts = [
        'total_fee' => 'integer',
        'date_bill' => 'date',
        'is_paid' => 'boolean',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function priceSupplier(): BelongsTo
    {
        return $this->belongsTo(PriceSupplier::class, 'price_supplier_id');
    }

    public function sablon(): BelongsTo
    {
        return $this->belongsTo(Sablon::class, 'sablon_id');
    }
}
