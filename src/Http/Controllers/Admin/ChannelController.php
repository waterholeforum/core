<?php

namespace Waterhole\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Channel;

/**
 * Controller for admin channel management (create and update).
 *
 * Deletion is handled by the DeleteChannel action.
 */
class ChannelController extends Controller
{
    public function create()
    {
        return view('waterhole::admin.structure.channel');
    }

    public function store(Request $request)
    {
        return $this->save(new Channel(), $request);
    }

    public function edit(Channel $channel)
    {
        return view('waterhole::admin.structure.channel', compact('channel'));
    }

    public function update(Channel $channel, Request $request)
    {
        return $this->save($channel, $request);
    }

    private function save(Channel $channel, Request $request)
    {
        $data = $request->validate(Channel::rules($channel));

        if (! $request->input('custom_filters')) {
            $data['filters'] = null;
        }

        $icon = Arr::pull($data, 'icon');
        $permissions = Arr::pull($data, 'permissions');

        DB::transaction(function () use ($channel, $data, $permissions, $icon) {
            $channel->fill($data)->save();
            $channel->saveIcon($icon);
            $channel->savePermissions($permissions);
        });

        return redirect()->route('waterhole.admin.structure');
    }
}
