<?php

namespace Waterhole\Extend;

use Illuminate\Support\Facades\Auth;
use Waterhole\Extend\Concerns\ClassList;
use Waterhole\Models\Comment;

/**
 * A list of CSS classes to be applied to comment elements.
 */
abstract class CommentClasses
{
    use ClassList;
}

CommentClasses::add('is-unread', fn(Comment $comment) => $comment->isUnread());
CommentClasses::add('is-read', fn(Comment $comment) => $comment->isRead());
CommentClasses::add('is-mine', fn(Comment $comment) => $comment->user_id === Auth::id());
CommentClasses::add('has-replies', fn(Comment $comment) => $comment->reply_count);
