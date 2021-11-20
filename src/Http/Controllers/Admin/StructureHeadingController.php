<?php

namespace Waterhole\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\StructureHeading;

class StructureHeadingController extends Controller
{
    public function create()
    {
        return view('waterhole::admin.structure.heading');
    }

    public function store(Request $request)
    {
        StructureHeading::create(
            $request->validate(StructureHeading::rules())
        );

        return redirect()->route('waterhole.admin.structure');
    }

    public function edit(StructureHeading $heading)
    {
        return view('waterhole::admin.structure.heading', compact('heading'));
    }

    public function update(StructureHeading $heading, Request $request)
    {
        $heading->update(
            $request->validate(StructureHeading::rules($heading))
        );

        return redirect()->route('waterhole.admin.structure');
    }
}
