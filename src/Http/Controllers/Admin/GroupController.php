<?php

namespace Waterhole\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Waterhole\Forms\GroupForm;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Group;

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
        $form = $this->form(new Group());

        return view('waterhole::admin.groups.form', compact('form'));
    }

    public function store(Request $request)
    {
        $this->form(new Group())->submit($request);

        return redirect()->route('waterhole.admin.groups.index');
    }

    public function edit(Group $group)
    {
        abort_if(!$group->isCustom(), 404);

        $form = $this->form($group);

        return view('waterhole::admin.groups.form', compact('form', 'group'));
    }

    public function update(Group $group, Request $request)
    {
        $this->form($group)->submit($request);

        return redirect()->route('waterhole.admin.groups.index');
    }

    private function form(Group $group)
    {
        return new GroupForm($group);
    }
}
