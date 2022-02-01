<?php

namespace Waterhole\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\StructureLink;

/**
 * Controller for admin structure link management (create and update).
 *
 * Deletion is handled by the DeleteStructure action.
 */
class StructureLinkController extends Controller
{
    public function create()
    {
        return view('waterhole::admin.structure.link');
    }

    public function store(Request $request)
    {
        return $this->save(new StructureLink(), $request);
    }

    public function edit(StructureLink $link)
    {
        return view('waterhole::admin.structure.link', compact('link'));
    }

    public function update(StructureLink $link, Request $request)
    {
        return $this->save($link, $request);
    }

    private function save(StructureLink $link, Request $request)
    {
        $data = $request->validate(StructureLink::rules($link));

        $icon = Arr::pull($data, 'icon');
        $permissions = Arr::pull($data, 'permissions');

        DB::transaction(function () use ($link, $data, $permissions, $icon) {
            $link->fill($data)->save();
            $link->saveIcon($icon);
            $link->savePermissions($permissions);
        });

        return redirect()->route('waterhole.admin.structure');
    }
}
