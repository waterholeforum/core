<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\IndexFooterLanguage;

/**
 * A list of components to render in the index footer.
 */
abstract class IndexFooter
{
    use OrderedList;
}

IndexFooter::add(IndexFooterLanguage::class, 0, 'language');
