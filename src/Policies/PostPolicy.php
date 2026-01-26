<?php

namespace Waterhole\Policies;

use Waterhole\Models\Post;
use Waterhole\Models\User;

class PostPolicy
{
    /**
     * Any user can create a post.
     */
    public function create(): bool
    {
        return true;
    }

    /**
     * Users can moderate a post and its comments if they have permission.
     */
    public function moderate(User $user, Post $post): bool
    {
        return $user->can('waterhole.channel.moderate', $post->channel);
    }

    /**
     * Users can edit their own posts. Users with the `moderate` ability can
     * edit any posts.
     */
    public function edit(?User $user, Post $post): bool
    {
        if (!$user || $post->trashed()) {
            return false;
        }

        if ($this->moderate($user, $post)) {
            return true;
        }

        if ($post->user_id !== $user->id) {
            return false;
        }

        $limitMinutes = config('waterhole.forum.edit_time_limit');

        if ($limitMinutes === null) {
            return true;
        }

        return $post->created_at->diffInMinutes(now()) <= $limitMinutes;
    }

    /**
     * Users can delete their own posts if they're uncommented on. Users with
     * the `moderate` ability can delete any posts.
     */
    public function delete(User $user, Post $post): bool
    {
        return ($post->user_id === $user->id && $post->comment_count === 0) ||
            $this->moderate($user, $post);
    }

    /**
     * Users can move their own posts if they're uncommented on. Users with
     * the `moderate` ability can move any posts.
     */
    public function move(User $user, Post $post): bool
    {
        return $this->delete($user, $post);
    }

    /**
     * Users can comment on a post if they have permission and the post is not
     * locked.
     */
    public function comment(User $user, Post $post): bool
    {
        return !$post->trashed() &&
            $post->is_approved &&
            $user->can('waterhole.channel.comment', $post->channel) &&
            (!$post->is_locked || $this->moderate($user, $post));
    }

    /**
     * Any user can react to a post.
     */
    public function react(User $user, Post $post): bool
    {
        return !$post->trashed();
    }
}
