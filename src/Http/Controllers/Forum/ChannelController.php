<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Channel;
use Waterhole\PostFeed;

class ChannelController extends Controller
{
    public function __construct()
    {
        $this->middleware('waterhole.auth')->except('show');
    }

    public function show(Channel $channel, Request $request)
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

        return view('waterhole::channels.show', compact('channel', 'feed'));
    }

    public function create()
    {
        $this->authorize('create', Channel::class);

        return view('waterhole::channels.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Channel::class);

        $channel = Channel::create(
            $this->data($request)
        );

        return redirect($channel->url);
    }

    public function edit(Channel $channel)
    {
        $this->authorize('update', $channel);

        return view('waterhole::channels.edit', ['channel' => $channel]);
    }

    public function update(Channel $channel, Request $request)
    {
        $this->authorize('update', $channel);

        $channel->update(
            $this->data($request, $channel)
        );

        return redirect($channel->url);
    }

    private function data(Request $request, Channel $channel = null): array
    {
        $data = $request->validate(Channel::rules($channel));

        if ($data['emoji']) {
            $data['icon'] = $data['emoji'];
            unset($data['emoji']);
        }

        if (! $request->get('custom_sorts')) {
            $data['sorts'] = $data['default_sort'] = null;
        }

        return $data;
    }
}
