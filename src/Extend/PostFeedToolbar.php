<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Views\Components\FeedControls;
use Waterhole\Views\Components\FeedNewPost;
use Waterhole\Views\Components\FeedSort;
use Waterhole\Views\Components\FeedTopPeriod;
use Waterhole\Views\Components\Spacer;

/**
 * A list of components to render in the post feed toolbar.
 */
abstract class PostFeedToolbar
{
    use OrderedList;
}

PostFeedToolbar::add('sort', FeedSort::class);
PostFeedToolbar::add('top-period', FeedTopPeriod::class);
PostFeedToolbar::add('spacer', Spacer::class);
PostFeedToolbar::add('controls', FeedControls::class);
PostFeedToolbar::add('new-post', FeedNewPost::class);
