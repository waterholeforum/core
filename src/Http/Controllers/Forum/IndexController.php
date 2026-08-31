<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Waterhole\Feed\PostFeed;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Http\Middleware\MaybeRequireLogin;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;

/**
 * Controller for the forum home, channels, and pages.
 */
class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware(MaybeRequireLogin::class)->only('home');
    }

    public function home()
    {
        return view('waterhole::forum.home');
    }

    public function channel(Channel $channel, Request $request)
    {
        $feed = PostFeed::forIndex(
            request: $request,
            filters: $channel->filters ?: config('waterhole.forum.post_filters', []),
            layout: resolve($channel->layout ?: config('waterhole.forum.post_layout')),
            scope: function (Builder $query) use ($channel, $request) {
                $query->where('posts.channel_id', $channel->id);
                $this->applyTagFilters($query, $channel, $request);
            },
            channel: $channel,
        );

        return view('waterhole::forum.channel', compact('channel', 'feed'));
    }

    public function page(Page $page)
    {
        return view('waterhole::forum.page', compact('page'));
    }

    private function applyTagFilters(Builder $query, Channel $channel, Request $request): void
    {
        $selections = $request->query('tags', []);

        if ($selections === []) {
            return;
        }

        abort_if(
            validator(['tags' => $selections], [
                'tags' => ['array'],
                'tags.*' => ['array'],
                'tags.*.*' => ['integer'],
            ])->fails(),
            404,
        );

        $selections = collect($selections)->map(fn($ids) => collect($ids)->unique());

        $taxonomies = $channel
            ->taxonomies()
            ->with(['tags' => fn($query) => $query->whereKey($selections->flatten())])
            ->findMany($selections->keys());

        abort_if(
            $taxonomies->count() !== $selections->count()
            || $selections->contains(
                fn($ids, $taxonomyId) => (
                    $taxonomies->find($taxonomyId)->tags->count() !== $ids->count()
                ),
            ),
            404,
        );

        foreach ($selections as $ids) {
            if ($ids->isEmpty()) {
                continue;
            }

            $query->whereHas('tags', fn($query) => $query->whereKey($ids));
        }
    }
}
