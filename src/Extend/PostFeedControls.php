<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\PostFeedControlsLayout;

/**
 * A list of components to render in the post feed controls popup.
 */
abstract class PostFeedControls
{
    use OrderedList;
}

PostFeedControls::add(PostFeedControlsLayout::class, 0, 'layout');
