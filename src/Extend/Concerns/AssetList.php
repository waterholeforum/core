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
     * that uses the Waterhole layout. The `cp` and `cp-{locale}` bundles
     * are loaded on pages in the Control Panel.
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
        $files = [];

        foreach ($bundles as $bundle) {
            if (config('app.debug')) {
                static::flushBundle($bundle);
            }

            $key = static::cacheKey($bundle);

            $files = array_merge(
                $files,
                Cache::rememberForever($key, function () use ($bundle) {
                    $assets = static::$assets[$bundle] ?? [];
                    return $assets ? [static::compile($assets, $bundle)] : [];
                }),
            );
        }

        return array_map(fn($file) => asset(Storage::disk('public')->url($file)), $files);
    }

    /**
     * Flush all bundles so they are regenerated on the next request.
     */
    public static function flush(): void
    {
        foreach (static::$assets as $bundle => $files) {
            static::flushBundle($bundle);
        }
    }

    /**
     * Flush a specific bundle so that is it regenerated on the next request.
     */
    public static function flushBundle(string $bundle): void
    {
        $key = static::cacheKey($bundle);

        if ($files = Cache::get($key)) {
            Storage::disk('public')->delete($files);
        }

        Cache::forget($key);
    }

    private static function cacheKey(string $bundle): string
    {
        return static::CACHE_KEY . '.' . $bundle;
    }

    private static function compile(array $assets, string $bundle): string
    {
        $content = '';

        foreach ($assets as $source) {
            if (is_callable($source) && ($output = $source())) {
                $content .= "$output\n";
            } else {
                $content .= file_get_contents($source) . "\n";
            }
        }

        $hash = substr(sha1($content), 0, 8);

        Storage::disk('public')->put(
            $compiled = static::FILE_EXTENSION . "/$bundle-$hash." . static::FILE_EXTENSION,
            $content,
        );

        return $compiled;
    }
}
