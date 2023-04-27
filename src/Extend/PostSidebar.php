<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;

/**
 * A list of components to render in the post page sidebar.
 */
abstract class PostSidebar
{
    use OrderedList;
}
