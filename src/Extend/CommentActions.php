<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\CommentReplyButton;

/**
 * A list of components to render in each comment's footer.
 */
abstract class CommentActions
{
    use OrderedList;
}

// CommentActions::add('react-button', CommentReactButton::class);
CommentActions::add('reply-button', CommentReplyButton::class);
