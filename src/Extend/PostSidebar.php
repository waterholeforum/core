<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\PostReactions;
use Waterhole\View\Components\PostReplies;

/**
 * A list of components to render in the post page sidebar.
 */
abstract class PostSidebar
{
    use OrderedList;
}

PostSidebar::add('reactions', PostReactions::class, position: -100);
PostSidebar::add('replies', PostReplies::class, position: -90);
