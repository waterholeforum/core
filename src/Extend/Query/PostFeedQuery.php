<?php

namespace Waterhole\Extend\Query;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Waterhole\Extend\Support\Set;

/**
 * Base post feed query callbacks.
 *
 * Use this extender to eager load relations or constrain feed results globally.
 */
class PostFeedQuery extends Set
{
    public function __construct()
    {
        $this->add(function (Builder $query) {
            $query->with([
                'user.groups',
                'channel.postsReactionSet.reactionTypes',
                'lastComment.user',
                'tags',
                'reactionCounts',
            ]);

            if (Auth::check()) {
                $query->with(['userState', 'channel.userState'])->withUnreadCommentsCount();
            }
        });
    }
}
