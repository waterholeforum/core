<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\Set;
use Waterhole\Filters\Alphabetical;
use Waterhole\Filters\Latest;
use Waterhole\Filters\Newest;
use Waterhole\Filters\Oldest;
use Waterhole\Filters\Top;
use Waterhole\Filters\Trending;

/**
 * A list of filters that can be applied to post feeds, to make available for
 * selection when configuring a Channel.
 */
abstract class PostFilters
{
    use Set;
}

PostFilters::add(Latest::class);
PostFilters::add(Newest::class);
PostFilters::add(Oldest::class);
PostFilters::add(Trending::class);
PostFilters::add(Top::class);
PostFilters::add(Alphabetical::class);
