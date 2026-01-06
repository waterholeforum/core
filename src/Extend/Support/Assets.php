<?php

namespace Waterhole\Extend\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Support class for asset extenders.
 *
 * Asset extenders use this to bundle, compile, and cache output.
 */
abstract class Assets
{
    private array $assets = [];

    /**
     * Add an asset to a bundle.
     *
     * The `default` and `default-{locale}` bundles are loaded on every page
     * that uses the Waterhole layout. The `cp` and `cp-{locale}` bundles
     * are loaded on pages in the Control Panel.
     */
    public function add(string $file, string $bundle = 'default'): void
    {
        if (!in_array($file, $this->assets[$bundle] ?? [])) {
            $this->assets[$bundle][] = $file;
        }
    }

    /**
     * Compile the given bundles and return their URLs.
     */
    public function urls(array $bundles): array
    {
        $files = [];

        foreach ($bundles as $bundle) {
            if (config('app.debug')) {
                $this->flushBundle($bundle);
            }

            $key = $this->cacheKey($bundle);

            $files = array_merge(
                $files,
                Cache::rememberForever($key, function () use ($bundle) {
                    $assets = $this->assets[$bundle] ?? [];
                    return $assets ? [$this->compile($assets, $bundle)] : [];
                }),
            );
        }

        $disk = config('waterhole.system.assets_disk');

        return array_map(fn($file) => asset(Storage::disk($disk)->url($file)), $files);
    }

    /**
     * Flush all bundles so they are regenerated on the next request.
     */
    public function flush(): void
    {
        foreach ($this->assets as $bundle => $files) {
            $this->flushBundle($bundle);
        }
    }

    /**
     * Flush a specific bundle so that is it regenerated on the next request.
     */
    public function flushBundle(string $bundle): void
    {
        $key = $this->cacheKey($bundle);

        if ($files = Cache::get($key)) {
            Storage::disk(config('waterhole.system.assets_disk'))->delete($files);
        }

        Cache::forget($key);
    }

    abstract protected function cacheKey(string $bundle): string;

    abstract protected function filePath(string $filename): string;

    private function compile(array $assets, string $bundle): string
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

        Storage::disk(config('waterhole.system.assets_disk'))
            ->put($compiled = $this->filePath("$bundle-$hash"), $content);

        return $compiled;
    }
}
