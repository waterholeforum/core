<?php

namespace Waterhole\Extend\Concerns;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Manage a list of assets grouped into bundles.
 *
 * @internal
 */
trait AssetList
{
    private static array $assets = [];

    /**
     * Add an asset to a bundle.
     *
     * The `default` and `default-{locale}` bundles are loaded on every page
     * that uses the Waterhole layout. The `admin` and `admin-{locale}` bundles
     * are loaded on pages in the Admin Panel.
     */
    public static function add(string $file, string $bundle = 'default'): void
    {
        if (!in_array($file, static::$assets[$bundle] ?? [])) {
            static::$assets[$bundle][] = $file;
        }
    }

    /**
     * Compile the given bundles and return their URLs.
     */
    public static function urls(array $bundles): array
    {
        $urls = [];

        foreach ($bundles as $bundle) {
            $key = static::CACHE_KEY . '.' . $bundle;

            if (config('app.debug')) {
                Cache::forget($key);
            }

            $urls = array_merge(
                $urls,
                Cache::rememberForever($key, function () use ($bundle) {
                    $assets = static::$assets[$bundle] ?? [];
                    return $assets ? static::compile($assets, $bundle) : [];
                }),
            );
        }

        return $urls;
    }

    private static function compile(array $assets, string $bundle): array
    {
        $content = '';

        foreach ($assets as $source) {
            if (is_callable($source)) {
                $content .= $source() . "\n";
            } else {
                $content .= file_get_contents($source) . "\n";
            }
        }

        Storage::disk('public')->put($compiled = static::FILE_EXTENSION."/$bundle.".static::FILE_EXTENSION, $content);

        return [asset(Storage::disk('public')->url($compiled))];
    }
}
