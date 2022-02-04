<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Views\Components\IndexFooter;
use Waterhole\Views\Components\IndexNav;

/**
 * A list of components to render in the index sidebar.
 */
abstract class IndexSidebar
{
    use OrderedList;
}

IndexSidebar::add('nav', IndexNav::class);
IndexSidebar::add('footer', IndexFooter::class, 100);
