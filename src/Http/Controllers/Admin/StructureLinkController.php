<?php

namespace Waterhole\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\StructureLink;

class StructureLinkController extends Controller
{
    public function create()
    {
        return view('waterhole::admin.structure.link');
    }

    public function store(Request $request)
    {
        $data = $request->validate(StructureLink::rules());
        $permissions = Arr::pull($data, 'permissions');
        $icon = Arr::pull($data, 'icon');

        $link = StructureLink::create($data);
        $link->savePermissions($permissions);
        $link->saveIcon($icon);

        return redirect()->route('waterhole.admin.structure');
    }

    public function edit(StructureLink $link)
    {
        return view('waterhole::admin.structure.link', compact('link'));
    }

    public function update(StructureLink $link, Request $request)
    {
        $data = $request->validate(StructureLink::rules($link));
        $permissions = Arr::pull($data, 'permissions');
        $icon = Arr::pull($data, 'icon');

        $link->update($data);
        $link->savePermissions($permissions);
        $link->saveIcon($icon);

        return redirect()->route('waterhole.admin.structure');
    }
}
