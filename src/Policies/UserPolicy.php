<?php

namespace Waterhole\Policies;

use Waterhole\Models\User;

class UserPolicy
{
    /**
     * Users can delete themselves.
     */
    public function delete(User $actor, User $user): bool
    {
        return $actor->is($user);
    }
}
