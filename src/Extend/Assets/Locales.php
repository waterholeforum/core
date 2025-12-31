<?php

namespace Waterhole\Extend\Assets;

use Waterhole\Extend\Support\UnorderedList;

/**
 * List of locales exposed in the language selector and locale assets.
 *
 * Use this extender to add locale packs and expose them in the language picker.
 */
class Locales extends UnorderedList
{
    public function __construct()
    {
        $this->add('English', 'en');
    }
}
