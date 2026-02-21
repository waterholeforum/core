<?php

namespace Waterhole\Http\Controllers\Cp;

use function Waterhole\internal_url;

use Illuminate\Http\Request;
use Waterhole\Forms\ReactionSetForm;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\ReactionSet;

/**
 * Controller for CP reaction set management.
 */
class ReactionSetController extends Controller
{
    public function index()
    {
        return view('waterhole::cp.reactions.index', [
            'reactionSets' => ReactionSet::with('reactionTypes')->get(),
        ]);
    }

    public function create()
    {
        $form = $this->form(new ReactionSet());

        return view('waterhole::cp.reactions.reaction-set', compact('form'));
    }

    public function store(Request $request)
    {
        $this->form($reactionSet = new ReactionSet())->submit($request);

        return redirect($reactionSet->edit_url);
    }

    public function edit(ReactionSet $reactionSet)
    {
        $form = $this->form($reactionSet);

        return view('waterhole::cp.reactions.reaction-set', compact('form', 'reactionSet'));
    }

    public function update(ReactionSet $reactionSet, Request $request)
    {
        $this->form($reactionSet)->submit($request);

        return redirect(
            internal_url($request->input('return'), route('waterhole.cp.reaction-sets.index')),
        )->with(
            'success',
            __('waterhole::cp.reaction-set-saved-message'),
        );
    }

    private function form(ReactionSet $reactionSet)
    {
        return new ReactionSetForm($reactionSet);
    }
}
