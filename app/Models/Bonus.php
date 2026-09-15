<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Bonus extends Model
{
    use Userstamps, SoftDeletes;

    protected $fillable = [
        'min',
        'bonus',
        'notes',
    ];

    protected $casts = [
        'min'   => 'integer',
        'bonus' => 'integer',
    ];
}
