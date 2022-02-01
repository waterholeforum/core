<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Views\Components\PostReactButton;
use Waterhole\Views\Components\PostReactions;
use Waterhole\Views\Components\PostReplies;

/**
 * A list of components to render in each post's footer.
 */
abstract class PostFooter
{
    use OrderedList;
}

PostFooter::add('reactions', PostReactions::class);
PostFooter::add('replies', PostReplies::class);
PostFooter::add('react-button', PostReactButton::class);
