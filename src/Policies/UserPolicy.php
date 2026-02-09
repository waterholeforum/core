<?php

namespace Waterhole\Policies;

use Waterhole\Models\User;
use Waterhole\Waterhole;

class UserPolicy
{
    public function mention(User $user, User $target): bool
    {
        return true;
    }

    /**
     * Users can suspend other users if they have permission and the target
     * doesn't have the same permission.
     */
    public function suspend(User $user, User $target): bool
    {
        return Waterhole::permissions()->can($user, 'suspend', User::class) &&
            !Waterhole::permissions()->can($target, 'suspend', User::class);
    }
}
