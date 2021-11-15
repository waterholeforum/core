<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Channel;
use Waterhole\PostFeed;

class FeedController extends Controller
{
    public function home(Request $request)
    {
        $scope = function (Builder $query) {
            $query->whereDoesntHave('userState', function ($query) {
                $query->where('notifications', 'ignore');
            });

            $query->whereDoesntHave('channel.userState', function ($query) {
                $query->where('notifications', 'ignore');
            });
        };

        $feed = new PostFeed(
            request: $request,
            scope: $scope,
        );

        return view('waterhole::forum.home')->with(compact('feed'));
    }

    public function channel(Channel $channel, Request $request)
    {
        $this->authorize('view', $channel);

        $feed = new PostFeed(
            request: $request,
            scope: function (Builder $query) use ($channel) {
                $query->where('posts.channel_id', $channel->id);
            },
            sorts: $channel->sorts,
            defaultSort: $channel->default_sort,
            defaultLayout: $channel->default_layout,
        );

        return view('waterhole::forum.channel', compact('channel', 'feed'));
    }
}
