<?php

namespace Waterhole\Extend;

use Illuminate\Database\Eloquent\Builder;
use Waterhole\Extend\Concerns\Set;

/**
 *
 */
abstract class PostFeedQuery
{
    use Set;
}

PostFeedQuery::add(function (Builder $query) {
    $query->with([
        'user.groups',
        'channel.userState',
        'channel.postsReactionSet.reactionTypes',
        'lastComment.user',
        'userState',
        'tags',
        'mentions',
        'attachments',
        'reactionsSummary',
    ]);

    $query->withUnreadCommentsCount();
});
