<?php

namespace Waterhole\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Waterhole\Forms\ChannelForm;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Http\Controllers\Cp\Concerns\CreatesStructureChildren;
use Waterhole\Models\Channel;

use function Waterhole\internal_url;

/**
 * Controller for CP channel management (create and update).
 *
 * Deletion is handled by the DeleteChannel action.
 */
class ChannelController extends Controller
{
    use CreatesStructureChildren;

    public function create(Request $request)
    {
        $this->structureParent($request);
        $form = $this->form(new Channel());

        return view('waterhole::cp.structure.channel', compact('form'));
    }

    public function store(Request $request)
    {
        $parent = $this->structureParent($request);
        $this->form($channel = new Channel())->submit($request);
        $this->appendToStructureParent($channel->structure, $parent);

        return redirect(internal_url($request->input('return'), route('waterhole.cp.structure')));
    }

    public function edit(Channel $channel)
    {
        $form = $this->form($channel);

        return view('waterhole::cp.structure.channel', compact('form', 'channel'));
    }

    public function update(Channel $channel, Request $request)
    {
        $this->form($channel)->submit($request);

        return redirect(internal_url($request->input('return'), route('waterhole.cp.structure')));
    }

    private function form(Channel $channel)
    {
        return new ChannelForm($channel);
    }
}
