<?php

namespace Waterhole\Extend;

use Illuminate\Support\Facades\Storage;
use Waterhole\Extend\Concerns\AssetList;

/**
 * Manage JavaScript asset bundles.
 *
 * In addition to files, you can also add callbacks which return JS code.
 *
 * Waterhole will simply concatenate the scripts together into bundles. You are
 * responsible for doing any transpiling prior.
 */
class Script
{
    use AssetList;

    const CACHE_KEY = 'waterhole.script';

    private static function compile(array $assets, string $bundle): array
    {
        $content = '';

        foreach ($assets as $source) {
            if (is_callable($source)) {
                $content .= $source() . ';';
            } else {
                $content .= file_get_contents($source) . ';';
            }
        }

        Storage::disk('public')->put($compiled = "js/$bundle.js", $content);

        return [asset(Storage::disk('public')->url($compiled))];
    }
}

Script::add(__DIR__ . '/../../resources/dist/index.js');
Script::add(__DIR__ . '/../../resources/dist/admin.js', bundle: 'admin');
