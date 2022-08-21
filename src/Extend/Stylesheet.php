<?php

namespace Waterhole\Extend;

use Less_Cache;
use Waterhole\Extend\Concerns\AssetList;

/**
 * Manage stylesheet asset bundles.
 *
 * Waterhole bundles CSS files together using a [Less CSS](https://lesscss.org)
 * compiler, so you can add both Less and CSS stylesheets.
 */
class Stylesheet
{
    use AssetList;

    private static function compile(array $assets, string $bundle): array
    {
        $files = array_combine($assets, array_fill(0, count($assets), url('/')));

        $compiled = Less_Cache::Get($files, [
            'cache_dir' => storage_path('app/public/css'),
            'prefix' => "$bundle-",
        ]);

        return [asset('storage/css/' . $compiled)];
    }
}

Stylesheet::add(__DIR__ . '/../../resources/less/forum/app.less');
Stylesheet::add(__DIR__ . '/../../resources/less/admin/app.less', bundle: 'admin');
