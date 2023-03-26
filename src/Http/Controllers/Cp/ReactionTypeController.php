<?php

namespace Waterhole\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Waterhole\Forms\ReactionTypeForm;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\ReactionSet;
use Waterhole\Models\ReactionType;

/**
 * Controller for CP reaction type management.
 */
class ReactionTypeController extends Controller
{
    public function reorder(ReactionSet $reactionSet, Request $request)
    {
        $request['order'] = json_decode($request->input('order'), true);

        $data = $request->validate(['order' => 'array']);

        if ($data['order']) {
            foreach ($data['order'] as $position => $id) {
                ReactionType::whereKey($id)->update(compact('position'));
            }
        }

        return redirect($reactionSet->edit_url);
    }
    public function create(ReactionSet $reactionSet)
    {
        $form = $this->form(new ReactionType(['icon' => 'emoji:']));

        return view('waterhole::cp.reactions.reaction-type', compact('form', 'reactionSet'));
    }

    public function store(ReactionSet $reactionSet, Request $request)
    {
        $reactionType = new ReactionType();
        $reactionType->reactionSet()->associate($reactionSet);

        $this->form($reactionType)->submit($request);

        return redirect($reactionSet->edit_url);
    }

    public function edit(ReactionSet $reactionSet, ReactionType $reactionType)
    {
        $form = $this->form($reactionType);

        return view(
            'waterhole::cp.reactions.reaction-type',
            compact('form', 'reactionSet', 'reactionType'),
        );
    }

    public function update(ReactionSet $reactionSet, ReactionType $reactionType, Request $request)
    {
        $this->form($reactionType)->submit($request);

        return redirect($request->input('return', $reactionType->reactionSet->edit_url))->with(
            'success',
            __('waterhole::cp.reaction-type-saved-message'),
        );
    }

    private function form(ReactionType $reactionType)
    {
        return new ReactionTypeForm($reactionType);
    }
}
