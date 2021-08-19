<?php

namespace Waterhole\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Waterhole\Models\Comment;
use Waterhole\Models\User;

class CommentPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user)
    {
        return $this->allow();
    }

    public function view(?User $user, Comment $comment)
    {
        return $this->allow();
    }

    public function create(User $user)
    {
        return $this->allow();
    }

    public function update(User $user, Comment $comment)
    {
        return $comment->user_id === $user->id;
    }

    public function delete(User $user, Comment $comment)
    {
        return $comment->user_id === $user->id;
    }

    public function reply(User $user, Comment $comment)
    {
        return $this->allow();
    }

    public function like(User $user, Comment $comment)
    {
        return $this->allow();
    }
}
