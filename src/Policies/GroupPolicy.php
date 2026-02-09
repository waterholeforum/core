<?php

namespace Waterhole\Policies;

use Waterhole\Models\Channel;
use Waterhole\Models\Enums\Mentionable;
use Waterhole\Models\Group;
use Waterhole\Models\User;

class GroupPolicy
{
    public function mention(User $user, Group $group, ?Channel $channel = null)
    {
        if (!$group->is_public) {
            return false;
        }

        if ($group->mentionable === Mentionable::Anyone) {
            return true;
        }

        if ($channel && $user->can('waterhole.channel.moderate', $channel)) {
            return true;
        }

        if (
            $group->mentionable === Mentionable::Members &&
            $user->groups->contains($group->getKey())
        ) {
            return $user->groups->contains($group->getKey());
        }
    }
}
