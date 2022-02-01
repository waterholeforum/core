<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\Set;
use Waterhole\Filters\Alphabetical;
use Waterhole\Filters\Latest;
use Waterhole\Filters\NewActivity;
use Waterhole\Filters\Oldest;
use Waterhole\Filters\Top;

/**
 * A list of filters that can be applied to post feeds, to make available for
 * selection when configuring a Channel.
 */
abstract class PostFilters
{
    use Set;
}

PostFilters::add(Latest::class);
PostFilters::add(NewActivity::class);
PostFilters::add(Oldest::class);
PostFilters::add(Top::class);
PostFilters::add(Alphabetical::class);
