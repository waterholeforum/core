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
        $path = $this->sourceDirectory();

        $this->add("$path/global.js");
        $this->add("$path/highlight.js");
        $this->add("$path/emoji.js");
        $this->add("$path/lightbox.js");

        $this->add("$path/cp.js", 'cp');
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
