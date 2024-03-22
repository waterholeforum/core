<?php

namespace Waterhole\Extend;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Waterhole\Extend\Concerns\Attributes;
use Waterhole\Models\Post;

/**
 * HTML attributes to be applied to post elements.
 */
abstract class PostAttributes
{
    use Attributes;
}

PostAttributes::add(
    fn(Post $post) => [
        'class' => Arr::toCssClasses([
            'is-unread' => $post->isUnread(),
            'is-read' => $post->isRead(),
            'is-new' => $post->isNew(),
            'is-mine' => $post->user_id === Auth::id(),
            'is-followed' => $post->isFollowed(),
            'is-ignored' => $post->isIgnored(),
            'has-replies' => $post->comment_count,
            'is-locked' => $post->is_locked,
        ]),
        'data-channel' => $post->channel->slug,
    ],
);
