<?php

namespace Waterhole\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Group;
use Waterhole\Models\Structure;

/**
 * Controller for admin group management (list, create, and update).
 *
 * Deletion is handled by the DeleteGroup action.
 */
class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::custom()
            ->withCount('users')
            ->orderBy('name')
            ->get();

        return view('waterhole::admin.groups.index', compact('groups'));
    }

    public function create()
    {
        return $this->form();
    }

    public function store(Request $request)
    {
        $this->save(new Group(), $request);
    }

    public function edit(Group $group)
    {
        abort_if(! $group->isCustom(), 404);

        return $this->form()->with(compact('group'));
    }

    public function update(Group $group, Request $request)
    {
        $this->save($group, $request);
    }

    private function form()
    {
        $structure = Structure::query()
            ->orderBy('position')
            ->get()
            ->loadMissing('content.permissions.recipient');

        // Construct an array of all abilities that apply to the structure
        // content to use as columns for the permission grid.
        $abilities = $structure->flatMap(function(Structure $node) {
            return method_exists($node->content, 'abilities') ? $node->content->abilities() : [];
        })->unique();

        return view('waterhole::admin.groups.form', compact('structure', 'abilities'));
    }

    private function save(Group $group, Request $request)
    {
        $data = $request->validate(Group::rules());

        $icon = Arr::pull($data, 'icon');
        $permissions = Arr::pull($data, 'permissions');

        DB::transaction(function () use ($group, $data, $permissions, $icon) {
            $group->fill($data)->save();
            $group->saveIcon($icon);
            $group->savePermissions($permissions);
        });

        return redirect()->route('waterhole.admin.groups.index');
    }
}
