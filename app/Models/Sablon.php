<?php

namespace App\Models;

use App\Enums\StatusSablonEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Sablon extends Model
{
    use Userstamps, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'fabric_id',
        'image_fabric_id',
        'type_color_id',
        'type_fabric_id',
        'price_employee_id',
        'total_long_fabric',
        'total_sablon',
        'date_sablon',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_long_fabric' => 'integer',
        'total_sablon'      => 'integer',
        'date_sablon'       => 'date',
        'status'            => StatusSablonEnum::class,
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function fabric(): BelongsTo
    {
        return $this->belongsTo(Fabric::class, 'fabric_id');
    }

    public function imageFabric(): BelongsTo
    {
        return $this->belongsTo(ImageFabric::class, 'image_fabric_id');
    }

    public function typeColor(): BelongsTo
    {
        return $this->belongsTo(TypeColor::class, 'type_color_id');
    }

    public function typeFabric(): BelongsTo
    {
        return $this->belongsTo(TypeFabric::class, 'type_fabric_id');
    }

    public function priceEmployee(): BelongsTo
    {
        return $this->belongsTo(PriceEmployee::class, 'price_employee_id');
    }

    public function sablonDetails(): HasMany
    {
        return $this->hasMany(SablonDetail::class, 'sablon_id');
    }

    public function sablonEmployeeDetails(): HasMany
    {
        return $this->hasMany(SablonEmployeeDetail::class, 'sablon_id');
    }

    public function billSupplier(): HasOne
    {
        return $this->hasOne(BillSupplier::class, 'sablon_id');
    }
}
