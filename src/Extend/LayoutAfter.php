<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;

/**
 * A list of components to render in the layout after the main content.
 */
abstract class LayoutAfter
{
    use OrderedList;
}
