<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPermission extends Model
{
    public $timestamps = false;

    protected $table = 'menu_permissions';

    protected $fillable = [
        'menu_id',
        'permission_id'
    ];
}
