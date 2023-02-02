<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\PostReactions;
use Waterhole\View\Components\PostReplies;

/**
 * A list of components to render in each post's footer.
 */
abstract class PostFooter
{
    use OrderedList;
}

PostFooter::add(PostReactions::class, position: -100, key: 'reactions');
PostFooter::add(PostReplies::class, position: -90, key: 'replies');
