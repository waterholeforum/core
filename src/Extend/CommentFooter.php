<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\CommentReactButton;
use Waterhole\View\Components\CommentReactions;
use Waterhole\View\Components\CommentReplies;
use Waterhole\View\Components\CommentReplyButton;
use Waterhole\View\Components\Spacer;

/**
 * A list of components to render in each comment's footer.
 */
abstract class CommentFooter
{
    use OrderedList;
}

CommentFooter::add('reactions', CommentReactions::class);
CommentFooter::add('replies', CommentReplies::class);
CommentFooter::add('spacer', Spacer::class);
CommentFooter::add('react-button', CommentReactButton::class);
CommentFooter::add('reply-button', CommentReplyButton::class);
