<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\PostFeedChannel;
use Waterhole\View\Components\PostFeedPinned;
use Waterhole\View\Components\PostFeedToolbar;

/**
 * A list of components to render in the post feed header.
 */
abstract class PostFeedHeader
{
    use OrderedList;
}

PostFeedHeader::add(PostFeedChannel::class, position: -100, key: 'channel');
PostFeedHeader::add(PostFeedPinned::class, position: -90, key: 'pinned');
PostFeedHeader::add(PostFeedToolbar::class, position: -80, key: 'toolbar');
