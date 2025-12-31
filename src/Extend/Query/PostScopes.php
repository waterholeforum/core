<?php

namespace Waterhole\Extend\Query;

use Illuminate\Database\Eloquent\Builder;
use Waterhole\Extend\Support\UnorderedList;
use Waterhole\Models\Channel;
use Waterhole\Models\User;

/**
 * Post visibility scopes.
 *
 * Use this extender to restrict channels or control trashed visibility rules.
 */
class PostScopes extends UnorderedList
{
    public function __construct()
    {
        $this->add(function (Builder $query, ?User $user) {
            if (!is_null($ids = Channel::allPermitted($user))) {
                $query->whereIn('channel_id', $ids);
            }
        }, 'channel');

        $this->add(function (Builder $query, ?User $user) {
            $query->withTrashed();

            if (!$user?->isAdmin()) {
                $query->whereNull('deleted_at');

                if (!is_null($ids = Channel::allPermitted($user, 'moderate'))) {
                    $query->orWhereIn('channel_id', $ids);
                }
            }
        }, 'trashed');
    }
}
