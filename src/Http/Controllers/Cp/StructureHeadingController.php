<?php

namespace Waterhole\Http\Controllers\Cp;

use function Waterhole\internal_url;

use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\StructureHeading;

/**
 * Controller for CP structure heading management (create and update).
 *
 * Deletion is handled by the DeleteStructure action.
 */
class StructureHeadingController extends Controller
{
    public function create()
    {
        return view('waterhole::cp.structure.heading');
    }

    public function store(Request $request)
    {
        StructureHeading::create($request->validate(StructureHeading::rules()));

        return redirect(internal_url($request->input('return'), route('waterhole.cp.structure')));
    }

    public function edit(StructureHeading $heading)
    {
        return view('waterhole::cp.structure.heading', compact('heading'));
    }

    public function update(StructureHeading $heading, Request $request)
    {
        $heading->update($request->validate(StructureHeading::rules($heading)));

        return redirect(internal_url($request->input('return'), route('waterhole.cp.structure')));
    }
}
