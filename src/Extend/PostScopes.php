<?php

namespace Waterhole\Extend;

use Illuminate\Database\Eloquent\Builder;
use Waterhole\Extend\Concerns\UnorderedList;
use Waterhole\Models\Channel;
use Waterhole\Models\User;

/**
 *
 */
abstract class PostScopes
{
    use UnorderedList;
}

PostScopes::add(function (Builder $query, ?User $user) {
    if (!is_null($ids = Channel::allPermitted($user))) {
        $query->whereIn('channel_id', $ids);
    }
}, 'channel');
