<?php

namespace Waterhole\Http\Controllers\Cp;

use function Waterhole\internal_url;

use Illuminate\Http\Request;
use Waterhole\Forms\StructureLinkForm;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\StructureLink;

/**
 * Controller for CP structure link management (create and update).
 *
 * Deletion is handled by the DeleteStructure action.
 */
class StructureLinkController extends Controller
{
    public function create()
    {
        $form = $this->form(new StructureLink());

        return view('waterhole::cp.structure.link', compact('form'));
    }

    public function store(Request $request)
    {
        $this->form(new StructureLink())->submit($request);

        return redirect(internal_url($request->input('return'), route('waterhole.cp.structure')));
    }

    public function edit(StructureLink $link)
    {
        $form = $this->form($link);

        return view('waterhole::cp.structure.link', compact('form', 'link'));
    }

    public function update(StructureLink $link, Request $request)
    {
        $this->form($link)->submit($request);

        return redirect(internal_url($request->input('return'), route('waterhole.cp.structure')));
    }

    private function form(StructureLink $link)
    {
        return new StructureLinkForm($link);
    }
}
