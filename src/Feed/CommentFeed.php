<?php

namespace Waterhole\Feed;

use Closure;
use Illuminate\Http\Request;
use Waterhole\Models\Comment;

class CommentFeed extends Feed
{
    public function __construct(Request $request, array $filters, ?Closure $scope = null)
    {
        $query = Comment::with([
            'post.userState',
            'post.channel',
            'post.channel.commentsReactionSet',
            'user.groups',
            'parent.user.groups',
            'parent.post',
            'reactions.reactionType',
            'reactions.user',
            'mentions',
            'attachments',
            'reactionCounts',
        ]);

        if ($scope) {
            $scope($query);
        }

        parent::__construct($request, $query, $filters);
    }
}
