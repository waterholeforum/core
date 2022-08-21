<?php

namespace Waterhole\Extend;

use Illuminate\Support\Facades\Auth;
use Waterhole\Extend\Concerns\ClassList;
use Waterhole\Models\Post;

/**
 * A list of CSS classes to be applied to post elements.
 */
abstract class PostClasses
{
    use ClassList;
}

PostClasses::add('is-unread', fn(Post $post) => $post->isUnread());
PostClasses::add('is-read', fn(Post $post) => $post->isRead());
PostClasses::add('is-new', fn(Post $post) => $post->isNew());
PostClasses::add('is-mine', fn(Post $post) => $post->user_id === Auth::id());
PostClasses::add('is-followed', fn(Post $post) => $post->userState?->followed_at);
PostClasses::add('is-ignored', fn(Post $post) => $post->userState?->ignored_at);
PostClasses::add('has-replies', fn(Post $post) => $post->comment_count);
PostClasses::add('is-locked', fn(Post $post) => $post->is_locked);
