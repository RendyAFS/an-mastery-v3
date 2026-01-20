<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class TypeFabric extends Model
{
    use Userstamps, SoftDeletes;

    protected $fillable = [
        'name',
        'notes',
    ];
}
