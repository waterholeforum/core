<?php

namespace Waterhole\Extend;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Waterhole\Extend\Concerns\Attributes;
use Waterhole\Models\Comment;

/**
 * HTML attributes to be applied to comment elements.
 */
abstract class CommentAttributes
{
    use Attributes;
}

CommentAttributes::add(
    fn(Comment $comment) => [
        'class' => Arr::toCssClasses([
            'is-unread' => $comment->isUnread(),
            'is-read' => $comment->isRead(),
            'is-mine' => $comment->user_id === Auth::id(),
            'is-answer' => $comment->isAnswer(),
            'has-replies' => $comment->reply_count,
        ]),
    ],
);
