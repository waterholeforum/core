<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\CommentReactions;
use Waterhole\View\Components\CommentReplies;

/**
 * A list of components to render in each comment's footer.
 */
abstract class CommentFooter
{
    use OrderedList;
}

CommentFooter::add(CommentReactions::class, -90, 'reactions');
CommentFooter::add(CommentReplies::class, -100, 'replies');
