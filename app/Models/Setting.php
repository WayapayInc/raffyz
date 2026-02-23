<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("settings.{$key}", function () use ($key, $default) {
            try {
                $setting = static::where('key', $key)->first();
                return $setting?->value ?? $default;
            } catch (\Exception $e) {
                return $default;
            }
        });
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value],
        );

        try {
            Cache::forget("settings.{$key}");
        } catch (\Exception $e) {
            // Ignore cache errors during setting updates
        }
    }

    public static function clearCache(): void
    {
        $keys = static::pluck('key');

        foreach ($keys as $key) {
            Cache::forget("settings.{$key}");
        }
    }
}
