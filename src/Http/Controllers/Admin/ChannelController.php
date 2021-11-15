<?php

namespace Waterhole\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Channel;

class ChannelController extends Controller
{
    public function create()
    {
        $this->authorize('create', Channel::class);

        return view('waterhole::admin.channels.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Channel::class);

        $channel = Channel::create(
            $this->data($request)
        );

        return redirect()->route('waterhole.admin.structure');
    }

    public function edit(Channel $channel)
    {
        $this->authorize('update', $channel);

        return view('waterhole::admin.channels.edit', compact('channel'));
    }

    public function update(Channel $channel, Request $request)
    {
        $this->authorize('update', $channel);

        $channel->update(
            $this->data($request, $channel)
        );

        return redirect()->route('waterhole.admin.structure');
    }

    private function data(Request $request, Channel $channel = null): array
    {
        $data = $request->validate(Channel::rules($channel));

        if (! $request->input('custom_sorts')) {
            $data['sorts'] = null;
        }

        return $data;
    }
}
