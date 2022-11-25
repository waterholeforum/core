<?php

namespace Waterhole\Extend;

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
    const FILE_EXTENSION = 'js';
}

Script::add(__DIR__ . '/../../resources/dist/index.js');

Script::add(__DIR__ . '/../../resources/dist/admin.js', bundle: 'admin');

Script::add(__DIR__ . '/../../resources/dist/highlight.js', bundle: 'defer');
Script::add(__DIR__ . '/../../resources/dist/emoji.js', bundle: 'defer');
