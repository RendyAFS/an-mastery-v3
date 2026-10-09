<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class AppSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    /**
     * Get a setting value with caching.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("app_setting:{$key}", function () use ($key, $default) {
            try {
                if (!Schema::hasTable('app_settings')) {
                    return $default;
                }
                return static::where('key', $key)->value('value') ?? $default;
            } catch (\Throwable $e) {
                return $default;
            }
        });
    }

    /**
     * Set / update a setting value and clear its cache.
     */
    public static function set(string $key, mixed $value, ?string $description = null): static
    {
        $data = ['value' => $value];
        if ($description !== null) {
            $data['description'] = $description;
        }

        $setting = static::updateOrCreate(
            ['key' => $key],
            $data
        );

        Cache::forget("app_setting:{$key}");
        return $setting;
    }
}
