<?php

namespace Waterhole\Locale;

use Illuminate\Translation\Translator;
use Waterhole\Waterhole;

class BaseTranslator extends Translator
{
    public function parseKey($key): array
    {
        if (
            Waterhole::isForumRoute()
            && (
                str_starts_with($key, 'validation.')
                || str_starts_with($key, 'auth.')
                || str_starts_with($key, 'pagination.')
                || str_starts_with($key, 'passwords.')
            )
        ) {
            $key = 'waterhole::'.$key;
        }

        return parent::parseKey($key);
    }
}
