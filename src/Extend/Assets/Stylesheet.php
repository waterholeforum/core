<?php

namespace Waterhole\Extend\Assets;

use Waterhole\Extend\Support\Assets;

/**
 * Stylesheet asset bundles.
 *
 * Add file paths or callbacks that return CSS; bundles are concatenated
 * and cached.
 */
class Stylesheet extends Assets
{
    public function __construct()
    {
        $this->add(__DIR__ . '/../../../resources/dist/global.css');

        $this->add(__DIR__ . '/../../../resources/dist/cp.css', bundle: 'cp');
    }

    protected function cacheKey(string $bundle): string
    {
        return "waterhole.stylesheet.$bundle";
    }

    protected function filePath(string $filename): string
    {
        return "css/$filename.css";
    }
}
