<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $primaryKey = 'key';
    protected $keyType    = 'string';
    public $incrementing  = false;

    protected $fillable = ['key', 'value'];

    /**
     * Ambil nilai setting berdasarkan key.
     * Hasilnya di-cache selama 1 jam agar tidak query database setiap request.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting:{$key}", 3600, function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? $default;
        });
    }

    /**
     * Set/update nilai setting dan hapus cache-nya.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting:{$key}");
    }

    /**
     * Cek apakah APP_VERSION di config berbeda dengan versi yang tersimpan di database.
     * Jika berbeda, artinya ada versi baru yang sudah di-deploy tapi belum di-acknowledge.
     */
    public static function hasNewVersion(): bool
    {
        $dbVersion     = static::get('app_version');
        $configVersion = config('app.version');

        return $dbVersion !== null && version_compare($configVersion, $dbVersion, '>');
    }

    /**
     * Sinkronkan versi database dengan config (dipanggil setelah admin acknowledge update).
     */
    public static function syncVersion(): void
    {
        static::set('app_version', config('app.version'));
    }
}
