<?php

namespace Waterhole\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Channel;

class ChannelController extends Controller
{
    public function create()
    {
        return view('waterhole::admin.structure.channels.create');
    }

    public function store(Request $request)
    {
        $data = $this->data($request);
        $permissions = Arr::pull($data, 'permissions');

        $channel = Channel::create($data);
        $channel->savePermissions($permissions);

        return redirect()->route('waterhole.admin.structure');
    }

    public function edit(Channel $channel)
    {
        return view('waterhole::admin.structure.channels.edit', compact('channel'));
    }

    public function update(Channel $channel, Request $request)
    {
        $data = $this->data($request, $channel);
        $permissions = Arr::pull($data, 'permissions');

        $channel->update($data);
        $channel->savePermissions($permissions);

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
