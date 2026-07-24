<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Supplier extends Model
{
    use Userstamps, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'contact',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sablons(): HasMany
    {
        return $this->hasMany(Sablon::class, 'supplier_id');
    }

    public function billSuppliers(): HasMany
    {
        return $this->hasMany(BillSupplier::class, 'supplier_id');
    }
}
