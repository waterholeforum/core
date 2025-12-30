<?php

namespace Waterhole\Extend\Core;

use Waterhole\Extend\Support\Set;
use Waterhole\Filters\Alphabetical;
use Waterhole\Filters\Latest;
use Waterhole\Filters\Newest;
use Waterhole\Filters\Oldest;
use Waterhole\Filters\Top;
use Waterhole\Filters\Trending;

/**
 * Post feed filter classes.
 *
 * Used by feed filter menus and channel configuration in the control panel.
 */
class PostFilters extends Set
{
    public function __construct()
    {
        $this->add(Latest::class);
        $this->add(Newest::class);
        $this->add(Oldest::class);
        $this->add(Trending::class);
        $this->add(Top::class);
        $this->add(Alphabetical::class);
    }
}
