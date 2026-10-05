<?php

namespace App\Models;

use App\Traits\BelongsToWorkshop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Bonus extends Model
{
    use Userstamps, SoftDeletes, BelongsToWorkshop;

    protected $fillable = [
        'workshop_id',
        'min',
        'bonus',
        'notes',
    ];

    protected $casts = [
        'min'   => 'integer',
        'bonus' => 'integer',
    ];
}
