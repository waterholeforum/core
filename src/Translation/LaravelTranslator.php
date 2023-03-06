<?php

namespace Waterhole\Translation;

use Illuminate\Translation\Translator;
use Waterhole\Waterhole;

class LaravelTranslator extends Translator
{
    public function parseKey($key): array
    {
        if (Waterhole::isWaterholeRoute() && str_starts_with($key, 'validation.')) {
            $key = "waterhole::$key";
        }

        return parent::parseKey($key);
    }
}
