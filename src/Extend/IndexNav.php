<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;

/**
 * A list of components to render in the index menu.
 */
abstract class IndexNav
{
    use OrderedList;
}
