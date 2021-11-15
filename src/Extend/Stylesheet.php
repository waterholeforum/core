<?php

namespace Waterhole\Extend;

use Less_Cache;
use Waterhole\Extend\Concerns\ManagesAssets;

class Stylesheet
{
    use ManagesAssets;

    private static array $assets = [
        'forum' => [
            __DIR__.'/../../resources/less/forum/app.less',
        ],
        'admin' => [
            __DIR__.'/../../resources/less/admin/app.less',
        ],
    ];

    public static function compile(array $assets, string $group): array
    {
        $files = array_combine(
            $assets,
            array_fill(0, count($assets), url('/'))
        );

        $compiled = Less_Cache::Get($files, [
            'cache_dir' => storage_path('app/public/css'),
            'prefix' => "$group-",
        ]);

        return [asset('storage/css/'.$compiled)];
    }
}
