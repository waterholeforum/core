<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Waterhole\Feed\PostFeed;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Http\Middleware\MaybeRequireLogin;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\Tag;

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
            scope: function (Builder $query) use ($channel) {
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
}
