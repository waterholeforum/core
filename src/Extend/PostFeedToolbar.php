<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Views\Components\FeedFilters;
use Waterhole\Views\Components\FeedTopPeriod;
use Waterhole\Views\Components\PostFeedControls;
use Waterhole\Views\Components\PostFeedCreate;
use Waterhole\Views\Components\Spacer;

/**
 * A list of components to render in the post feed toolbar.
 */
abstract class PostFeedToolbar
{
    use OrderedList;
}

PostFeedToolbar::add('sort', FeedFilters::class);
PostFeedToolbar::add('top-period', FeedTopPeriod::class);
PostFeedToolbar::add('spacer', Spacer::class);
PostFeedToolbar::add('controls', PostFeedControls::class);
PostFeedToolbar::add('new-post', PostFeedCreate::class);
