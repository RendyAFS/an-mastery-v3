<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Permission;

class Menu extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'icon',
        'url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->where('is_active', true)->orderBy('sort_order');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'menu_permissions');
    }
}
