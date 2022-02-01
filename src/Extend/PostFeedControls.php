<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Views\Components\FeedControlsLayout;

/**
 * A list of components to render in the post feed controls popup.
 */
abstract class PostFeedControls
{
    use OrderedList;
}

PostFeedControls::add('layout', FeedControlsLayout::class);
