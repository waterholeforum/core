<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\FeedFilters;
use Waterhole\View\Components\Spacer;
use Waterhole\View\Components\TagsFilter;

/**
 * A list of components to render in the post feed toolbar.
 */
abstract class PostFeedToolbar
{
    use OrderedList, OfComponents;
}

PostFeedToolbar::add(FeedFilters::class, position: -100, key: 'filters');
// PostFeedToolbar::add('top-period', FeedTopPeriod::class, position: -90);
PostFeedToolbar::add(Spacer::class, 0, 'spacer');
PostFeedToolbar::add(TagsFilter::class, 0, 'tags');
