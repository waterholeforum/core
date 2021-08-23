<?php

namespace Waterhole\Extend;

use Illuminate\Support\Facades\Auth;
use Waterhole\Extend\Concerns\ManagesClasses;
use Waterhole\Models\Comment;

class CommentClasses
{
    use ManagesClasses;

    protected static function defaultClasses(Comment $comment): array
    {
        return [
            'is-unread' => $isUnread = Auth::check() && $comment->post->userState->last_read_at < $comment->created_at,
            'is-read' => ! $isUnread,
            'is-mine' => $comment->user_id === Auth::id(),
            'has-replies' => $comment->reply_count,
        ];
    }
}
