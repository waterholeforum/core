<?php

namespace Waterhole\Extend;

use Illuminate\Support\Facades\Storage;
use Waterhole\Extend\Concerns\ManagesAssets;

class Script
{
    use ManagesAssets;

    private static array $assets = [
        'forum' => [
            __DIR__.'/../../resources/dist/index.js',
        ],
        'admin' => [
            __DIR__.'/../../resources/dist/admin.js',
        ],
    ];

    public static function compile(array $assets, string $group): array
    {
        // TODO: caching

        $content = '';

        foreach ($assets as $source) {
            if (is_callable($source)) {
                $content .= $source().';';
            } else {
                $content .= file_get_contents($source).';';
            }
        }

        Storage::disk('public')->put($compiled = 'js/'.$group.'.js', $content);

        return [asset(Storage::disk('public')->url($compiled))];
    }
}
