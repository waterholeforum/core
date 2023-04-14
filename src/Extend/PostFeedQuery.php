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
        'channel.postsReactionSet',
        'lastComment.user',
        'userState',
        'reactions.user',
        'tags',
        'mentions',
        'attachments',
    ]);

    $query->withUnreadCommentsCount();
});
