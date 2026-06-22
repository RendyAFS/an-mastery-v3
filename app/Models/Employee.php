<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Employee extends Model
{
    use Userstamps, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'contact',
        'notes',
    ];

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->withTrashed()->where($field ?? $this->getRouteKeyName(), $value)->first();
    }
}
