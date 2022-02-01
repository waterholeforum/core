<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Views\Components\PostFeedChannel;
use Waterhole\Views\Components\PostFeedToolbar;

/**
 * A list of components to render in the post feed header.
 */
abstract class PostFeedHeader
{
    use OrderedList;
}

PostFeedHeader::add('channel', PostFeedChannel::class);
PostFeedHeader::add('toolbar', PostFeedToolbar::class);
