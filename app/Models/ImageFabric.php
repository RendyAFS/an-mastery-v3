<?php

namespace App\Models;

use App\Traits\BelongsToWorkshop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ImageFabric extends Model implements HasMedia
{
    use Userstamps, SoftDeletes, InteractsWithMedia, BelongsToWorkshop;

    protected $fillable = [
        'workshop_id',
        'name',
        'notes',
    ];
}
