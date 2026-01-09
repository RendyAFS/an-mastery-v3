<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Presence extends Model
{
    use Userstamps;
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'week_of',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
        'total',
        'notes',
    ];

    protected $casts = [
        'week_of'   => 'datetime',
        'monday'    => 'integer',
        'tuesday'   => 'integer',
        'wednesday' => 'integer',
        'thursday'  => 'integer',
        'friday'    => 'integer',
        'saturday'  => 'integer',
        'sunday'    => 'integer',
        'total'     => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
