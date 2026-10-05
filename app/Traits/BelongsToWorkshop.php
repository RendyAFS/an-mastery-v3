<?php

namespace App\Traits;

use App\Models\Workshop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToWorkshop
{
    public static function bootBelongsToWorkshop(): void
    {
        static::creating(function ($model) {
            if (empty($model->workshop_id) && auth()->check() && auth()->user()->workshop_id) {
                $model->workshop_id = auth()->user()->workshop_id;
            }
        });

        static::addGlobalScope('workshop', function (Builder $builder) {
            if (auth()->check() && auth()->user()->workshop_id) {
                $builder->where($builder->getModel()->getTable() . '.workshop_id', auth()->user()->workshop_id);
            }
        });
    }

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class, 'workshop_id');
    }

    public function scopeWithoutWorkshop(Builder $query): Builder
    {
        return $query->withoutGlobalScope('workshop');
    }
}
