<?php

namespace Waterhole\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class PostPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user)
    {
        return $this->allow();
    }

    public function view(?User $user, Post $post)
    {
        return $this->allow();
    }

    public function create(User $user)
    {
        return $this->allow();
    }

    public function update(User $user, Post $post)
    {
        return $post->user_id === $user->id;
    }

    public function move(User $user, Post $post)
    {
        return $post->user_id === $user->id && $post->comment_count === 0;
    }

    public function delete(User $user, Post $post)
    {
        return $post->user_id === $user->id && $post->comment_count === 0;
    }

    public function reply(User $user, Post $post)
    {
        return ! $post->is_locked;
    }

    public function like(User $user, Post $post)
    {
        return $this->allow();
    }

    public function moderate(User $user, Post $post)
    {
        return false;
    }
}
