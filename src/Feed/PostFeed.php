<?php

namespace Waterhole\Feed;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Waterhole\Extend\Query\PostFeedQuery;
use Waterhole\Layouts\Layout;
use Waterhole\Models\Post;

class PostFeed extends Feed
{
    public Collection $layouts;

    public function __construct(
        Request $request,
        array $filters,
        public Layout $layout,
        ?Closure $scope = null,
    ) {
        $query = Post::query();

        if ($scope) {
            $scope($query);
        }

        foreach (resolve(PostFeedQuery::class)->values() as $scope) {
            $scope($query);
        }

        $this->layout->scope($query);

        parent::__construct($request, $query, $filters);
    }
}
