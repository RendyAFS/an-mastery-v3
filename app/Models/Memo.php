<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Memo extends Model
{
    use SoftDeletes, Userstamps;

    protected $fillable = [
        'employee_id',
        'name',
        'nominal',
        'date',
        'is_paid',
    ];

    protected $casts = [
        'date'    => 'date',
        'nominal' => 'integer',
        'is_paid' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
