<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function coverStyle(): HasOne
    {
        return $this->hasOne(SupplierCoverStyle::class);
    }

    public function resolvedCoverStyle(): array
    {
        $style   = $this->coverStyle;
        $palette = config('cover-styles.palette');
        $fallback = $palette[$this->id % count($palette)];

        return [
            'color_from' => $style->color_from ?? $fallback['from'],
            'color_to'   => $style->color_to ?? $fallback['to'],
            'icon'       => $style->icon ?? 'book-marked',
            'pattern'    => $style->pattern ?? 'stripes',
            'is_custom'  => (bool) $style,
        ];
    }
}
