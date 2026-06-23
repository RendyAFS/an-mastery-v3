<?php

namespace App\Models;

use App\Enums\StatusHistoryStockEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoryStock extends Model
{
    protected $fillable = [
        'fabric_detail_id',
        'status',
        'total',
        'notes',
    ];

    protected $casts = [
        'total'  => 'integer',
        'status' => StatusHistoryStockEnum::class,
    ];

    public function fabricDetail(): BelongsTo
    {
        return $this->belongsTo(FabricDetail::class, 'fabric_detail_id');
    }
}
