<?php

namespace Waterhole\Extend;

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
        'is-unread' => $comment->isUnread(),
        'is-read' => $comment->isRead(),
        'is-mine' => $comment->user_id === Auth::id(),
        'has-replies' => $comment->reply_count,
    ],
);
