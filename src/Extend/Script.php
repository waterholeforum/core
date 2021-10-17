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
    ];

    public static function compile(array $assets, string $group): array
    {
        // TODO: caching

        $content = '';

        foreach ($assets as $file) {
            $content .= file_get_contents($file);
        }

        Storage::disk('public')->put($compiled = 'js/'.$group.'.js', $content);

        return [Storage::disk('public')->url($compiled)];
    }
}
