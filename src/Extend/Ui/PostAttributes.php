<?php

namespace Waterhole\Extend\Ui;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Waterhole\Extend\Support\Attributes;
use Waterhole\Models\Post;

/**
 * HTML attributes applied to post wrappers.
 *
 * Use this extender to add, remove, or reorder components rendered in this
 * region of the UI.
 */
class PostAttributes extends Attributes
{
    public function __construct()
    {
        $this->add(
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
    }
}
