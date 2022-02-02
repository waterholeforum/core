<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\UnorderedList;

/**
 * A list of supported locales.
 */
abstract class Locales
{
    use UnorderedList;
}

Locales::add('en', 'English');
