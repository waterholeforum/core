<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Views\Components\IndexFooterLanguage;
use Waterhole\Views\Components\IndexFooterTheme;

/**
 * A list of components to render in the index footer.
 */
abstract class IndexFooter
{
    use OrderedList;
}

IndexFooter::add('theme', IndexFooterTheme::class);
IndexFooter::add('language', IndexFooterLanguage::class);
