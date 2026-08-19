<?php

namespace Waterhole\Feed;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Waterhole\Extend\Query\PostFeedQuery;
use Waterhole\Filters\Drafts;
use Waterhole\Filters\Following;
use Waterhole\Filters\Ignoring;
use Waterhole\Filters\Trash;
use Waterhole\Layouts\Layout;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;

use function Waterhole\resolve_all;

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

    public static function forIndex(
        Request $request,
        array $filters,
        Layout $layout,
        ?Closure $scope = null,
    ): self {
        $filters = resolve_all($filters);

        if ($user = Auth::user()) {
            $filters[] = new Drafts();
            $filters[] = new Following();
            $filters[] = new Ignoring();

            if ($user->isAdmin() || Channel::allPermitted($user, 'moderate')) {
                $filters[] = new Trash();
            }
        }

        return new self($request, $filters, $layout, function (Builder $query) use ($scope) {
            $query->withGlobalScope(Trash::EXCLUDE_TRASHED_SCOPE, fn(Builder $query) => $query->withoutTrashed());

            $scope?->__invoke($query);
        });
    }
}
