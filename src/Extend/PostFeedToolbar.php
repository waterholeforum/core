<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\FeedFilters;
use Waterhole\View\Components\FeedTopPeriod;
use Waterhole\View\Components\PostFeedControls;
use Waterhole\View\Components\Spacer;

/**
 * A list of components to render in the post feed toolbar.
 */
abstract class PostFeedToolbar
{
    use OrderedList;
}

PostFeedToolbar::add('filters', FeedFilters::class, position: -100);
PostFeedToolbar::add('top-period', FeedTopPeriod::class, position: -90);
PostFeedToolbar::add('spacer', Spacer::class);
PostFeedToolbar::add('controls', PostFeedControls::class, position: 100);
// PostFeedToolbar::add('new-post', PostFeedCreate::class);
