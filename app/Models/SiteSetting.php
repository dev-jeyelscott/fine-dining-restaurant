<?php

namespace App\Models;

use App\Rules\ValidSiteSettingValue;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property string $key
 * @property string $value
 * @property string $group
 */
#[Fillable(['key', 'value', 'group'])]
class SiteSetting extends Model
{
    private const CACHE_KEY = 'site-settings.key-value-map';

    protected static function booted(): void
    {
        static::saved(static function (): void {
            static::forgetCachedValues();
        });

        static::deleted(static function (): void {
            static::forgetCachedValues();
        });
    }

    /**
     * @param  Builder<SiteSetting>  $query
     * @return Builder<SiteSetting>
     */
    public function scopeGroup(Builder $query, string $group): Builder
    {
        return $query->where('group', $group);
    }

    public static function value(string $key, ?string $default = null): ?string
    {
        return static::keyValueMap()[$key] ?? $default;
    }

    /**
     * @return array<string, string|null>
     */
    public static function keyValueMap(): array
    {
        /** @var array<string, string|null> $settings */
        $settings = Cache::rememberForever(
            self::CACHE_KEY,
            static fn (): array => static::query()
                ->pluck('value', 'key')
                ->all(),
        );

        return $settings;
    }

    public static function forgetCachedValues(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Return public contact settings while excluding malformed link values.
     *
     * @return array<string, string|null>
     */
    public static function publicContactMap(): array
    {
        $settings = static::keyValueMap();

        foreach (ValidSiteSettingValue::publicLinkKeys() as $key) {
            if (
                array_key_exists($key, $settings)
                && ! ValidSiteSettingValue::accepts($key, $settings[$key])
            ) {
                unset($settings[$key]);
            }
        }

        return $settings;
    }
}
