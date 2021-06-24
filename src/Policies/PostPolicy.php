<?php

namespace Waterhole\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
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
        return $this->allow();
    }

    public function delete(User $user, Post $post)
    {
        return $this->allow();
    }
}
