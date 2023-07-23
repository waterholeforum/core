<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Waterhole\Feed\PostFeed;
use Waterhole\Filters\Following;
use Waterhole\Filters\Ignoring;
use Waterhole\Filters\Trash;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Http\Middleware\MaybeRequireLogin;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\Tag;

use function Waterhole\resolve_all;

/**
 * Controller for the forum index (home, channels, and pages).
 */
class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware(MaybeRequireLogin::class)->only('home');
    }

    public function home(Request $request)
    {
        // Hide posts that the user has ignored, and posts that are in channels
        // that the user has ignored, to ensure the Home post feed is clean and
        // relevant.
        $scope = function (Builder $query) {
            $this->scope($query);

            $query->withGlobalScope(
                Ignoring::EXCLUDE_IGNORED_SCOPE,
                fn($query) => $query->whereNot->ignoring(),
            );

            $query->whereDoesntHave('channel', fn($query) => $query->ignoring());
        };

        $feed = new PostFeed(
            request: $request,
            filters: $this->resolveFilters(config('waterhole.forum.post_filters', [])),
            layout: resolve(config('waterhole.forum.post_layout')),
            scope: $scope,
        );

        return view('waterhole::forum.home')->with(compact('feed'));
    }

    public function channel(Channel $channel, Request $request)
    {
        $feed = new PostFeed(
            request: $request,
            filters: $this->resolveFilters(
                $channel->filters ?: config('waterhole.forum.post_filters', []),
            ),
            layout: resolve($channel->layout ?: config('waterhole.forum.post_layout')),
            scope: function (Builder $query) use ($channel) {
                $this->scope($query);

                $query->where('posts.channel_id', $channel->id);

                $param = request('tags');
                if ($param && ($ids = is_array($param) ? Arr::flatten($param) : [$param])) {
                    Tag::findOrFail($ids);
                    $query->whereRelation('tags', fn($query) => $query->whereKey($ids));
                }
            },
        );

        return view('waterhole::forum.channel', compact('channel', 'feed'));
    }

    public function page(Page $page)
    {
        return view('waterhole::forum.page', compact('page'));
    }

    private function scope(Builder $query)
    {
        $query->withGlobalScope(
            Trash::EXCLUDE_TRASHED_SCOPE,
            fn($query) => $query->withoutTrashed(),
        );
    }

    private function resolveFilters(array $filters)
    {
        $filters = resolve_all($filters);

        if ($user = Auth::user()) {
            $filters[] = new Following();
            $filters[] = new Ignoring();

            if ($user->isAdmin() || Channel::allPermitted($user, 'moderate')) {
                $filters[] = new Trash();
            }
        }

        return $filters;
    }
}
