<?php

namespace Waterhole\Extend\Query;

use Illuminate\Database\Eloquent\Builder;
use Waterhole\Extend\Support\UnorderedList;
use Waterhole\Models\Channel;
use Waterhole\Models\User;

/**
 * Post visibility query callbacks.
 *
 * Use this extender to apply additional visibility constraints for posts.
 */
class PostVisibilityScopes extends UnorderedList
{
    public function __construct()
    {
        $this->add(function (Builder $query, ?User $user) {
            if (!is_null($ids = Channel::allPermitted($user))) {
                $query->whereIn('channel_id', $ids);
            }
        }, 'channel');
    }
}
