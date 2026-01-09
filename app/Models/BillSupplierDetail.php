<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillSupplierDetail extends Model
{
    protected $fillable = [
        'bill_supplier_id',
        'sablon_detail_id',
    ];

    public function billSupplier(): BelongsTo
    {
        return $this->belongsTo(BillSupplier::class, 'bill_supplier_id');
    }

    public function sablonDetail(): BelongsTo
    {
        return $this->belongsTo(SablonDetail::class, 'sablon_detail_id');
    }
}
