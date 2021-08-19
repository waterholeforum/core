<?php

namespace Waterhole\Extend;

use Illuminate\Support\Facades\Auth;
use Waterhole\Extend\Concerns\ManagesClasses;
use Waterhole\Models\Post;

class PostClasses
{
    use ManagesClasses;

    protected static function defaultClasses(Post $post): array
    {
        return [
            'is-unread' => $isUnread = Auth::check() && $post->userState->last_read_at < ($post->last_comment_at ?: $post->created_at),
            'is-read' => ! $isUnread,
            'is-new' => Auth::check() && ! $post->userState,
            'is-mine' => $post->user_id === Auth::id(),
            'has-replies' => $post->comment_count,
            'is-locked' => $post->is_locked,
        ];
    }
}
