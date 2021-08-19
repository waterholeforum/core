<?php

namespace Waterhole\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Waterhole\Models\Channel;
use Waterhole\Models\User;

class ChannelPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user)
    {
        return $this->allow();
    }

    public function view(?User $user, Channel $channel)
    {
        return $this->allow();
    }

    public function create(User $user)
    {
        return $this->allow();
    }

    public function update(User $user, Channel $channel)
    {
        return $this->allow();
    }

    public function delete(User $user, Channel $channel)
    {
        return $this->allow();
    }

    public function post(User $user, Channel $channel)
    {
        return $this->allow();
    }
}
