<?php

namespace Waterhole\Feed;

use Closure;
use Illuminate\Http\Request;
use Waterhole\Extend;
use Waterhole\Models\Comment;

class CommentFeed extends Feed
{
    public function __construct(Request $request, array $filters, ?Closure $scope = null)
    {
        $query = Comment::query();

        if ($scope) {
            $scope($query);
        }

        $extender = resolve(Extend\Query\CommentQuery::class);

        foreach ([...$extender->values(), ...$extender->feed->values()] as $scope) {
            $scope($query);
        }

        parent::__construct($request, $query, $filters);
    }
}
