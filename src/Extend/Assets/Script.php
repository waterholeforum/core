<?php

namespace Waterhole\Extend\Assets;

use Waterhole\Extend\Support\Assets;

/**
 * JavaScript asset bundles.
 *
 * Add file paths or callbacks that return JS; bundles are concatenated
 * and cached.
 */
class Script extends Assets
{
    public function __construct()
    {
        $this->add(__DIR__ . '/../../../resources/dist/index.js');
        $this->add(__DIR__ . '/../../../resources/dist/highlight.js');
        $this->add(__DIR__ . '/../../../resources/dist/emoji.js');

        $this->add(__DIR__ . '/../../../resources/dist/cp.js', bundle: 'cp');
    }

    protected function cacheKey(string $bundle): string
    {
        return "waterhole.script.$bundle";
    }

    protected function filePath(string $filename): string
    {
        return "js/$filename.js";
    }
}
