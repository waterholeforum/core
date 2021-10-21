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
            'is-unread' => $post->isUnread(),
            'is-read' => $post->isRead(),
            'is-new' => $post->isNew(),
            'is-mine' => $post->user_id === Auth::id(),
            'is-followed' => $post->userState?->followed_at,
            'is-ignored' => $post->userState?->ignored_at,
            'has-replies' => $post->comment_count,
            'is-locked' => $post->is_locked,
        ];
    }
}
