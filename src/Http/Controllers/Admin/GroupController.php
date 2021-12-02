<?php

namespace Waterhole\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Channel;
use Waterhole\Models\Group;
use Waterhole\Models\Structure;

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
        $data = $request->validate(Group::rules());
        $permissions = Arr::pull($data, 'permissions');
        $icon = Arr::pull($data, 'icon');

        DB::transaction(function () use ($data, $permissions, $icon) {
            $group = Group::create($data);
            $group->savePermissions($permissions);
            $group->saveIcon($icon);
        });

        return redirect()->route('waterhole.admin.groups.index');
    }

    public function edit(Group $group)
    {
        return $this->form()->with(compact('group'));
    }

    public function update(Group $group, Request $request)
    {
        $data = $request->validate(Group::rules());
        $permissions = Arr::pull($data, 'permissions');
        $icon = Arr::pull($data, 'icon');

        DB::transaction(function () use ($group, $data, $permissions, $icon) {
            $group->update($data);
            $group->savePermissions($permissions);
            $group->saveIcon($icon);
        });

        return redirect()->route('waterhole.admin.groups.index');
    }

    private function form()
    {
        $structure = Structure::with(['content' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Channel::class => ['permissions.recipient'],
            ]);
        }])
            ->orderBy('position')
            ->get();

        return view('waterhole::admin.groups.form', [
            'structure' => $structure,
            'abilities' => $structure->flatMap(fn(Structure $node) => method_exists($node->content, 'abilities') ? $node->content->abilities() : [])->unique(),
        ]);
    }
}
