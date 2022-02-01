<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Views\Components\CommentReactButton;
use Waterhole\Views\Components\CommentReactions;
use Waterhole\Views\Components\CommentReplies;
use Waterhole\Views\Components\CommentReplyButton;
use Waterhole\Views\Components\Spacer;

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
