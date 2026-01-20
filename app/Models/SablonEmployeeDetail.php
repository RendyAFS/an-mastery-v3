<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SablonEmployeeDetail extends Model
{
    protected $fillable = [
        'sablon_id',
        'fabric_detail_id',
        'employee_id',
        'fee',
        'additional_fee',
        'total',
        'is_change',
        'notes'
    ];

    protected $casts = [
        'is_change' => 'boolean',
    ];

    public function sablon(): BelongsTo
    {
        return $this->belongsTo(Sablon::class, 'sablon_id');
    }

    public function fabricDetail(): BelongsTo
    {
        return $this->belongsTo(FabricDetail::class, 'fabric_detail_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
