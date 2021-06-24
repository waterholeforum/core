<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;

class ChannelController extends Controller
{
    public function __construct()
    {
        $this->middleware('waterhole.auth')->only('create', 'store');
    }

    public function show(Channel $channel)
    {
        $this->authorize('view', $channel);

        return view('waterhole::channels.show', [
            'channel' => $channel,
            'posts' => $channel->posts()->latest()->cursorPaginate()
        ]);
    }

    public function create()
    {
        $this->authorize('create', Channel::class);

        return view('waterhole::channels.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Channel::class);

        $validated = $request->validate(Channel::rules());

        if ($validated['emoji']) {
            $validated['icon'] = $validated['emoji'];
            unset($validated['emoji']);
        }

        $channel = Channel::create($validated);

        return redirect($channel->url);
    }

    public function edit(Channel $channel)
    {
        $this->authorize('update', Channel::class);

        return view('waterhole::channels.edit', ['channel' => $channel]);
    }

    public function update(Channel $channel, Request $request)
    {
        $this->authorize('update', Channel::class);

        $validated = $request->validate(Channel::rules($channel));

        if ($validated['emoji']) {
            $validated['icon'] = $validated['emoji'];
            unset($validated['emoji']);
        }

        $channel->update($validated);

        return redirect($channel->url);
    }

    public function delete(Channel $channel)
    {
        $this->authorize('delete', $channel);

        return view('waterhole::channels.delete', ['channel' => $channel]);
    }

    public function destroy(Channel $channel, Request $request)
    {
        $this->authorize('delete', $channel);

        $validated = $request->validate([
            'move_posts' => ['boolean'],
            'channel_id' => ['required_if:move_posts,1', Rule::exists(Channel::class, 'id')],
        ]);

        if ($validated['move_posts'] ?? false) {
            $channel->posts()->update(['channel_id' => $validated['channel_id']]);
        }

        $channel->delete();

        // TODO: update nav

        return redirect('/');
    }
}
