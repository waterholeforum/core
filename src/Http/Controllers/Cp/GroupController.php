<?php

namespace Waterhole\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Waterhole\Forms\GroupForm;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Group;

/**
 * Controller for CP group management (list, create, and update).
 *
 * Deletion is handled by the DeleteGroup action.
 */
class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::withCount('users')->get();

        return view('waterhole::cp.groups.index', compact('groups'));
    }

    public function create()
    {
        $form = $this->form(new Group());

        return view('waterhole::cp.groups.form', compact('form'));
    }

    public function store(Request $request)
    {
        $this->form(new Group())->submit($request);

        return redirect()->route('waterhole.cp.groups.index');
    }

    public function edit(Group $group)
    {
        $form = $this->form($group);

        return view('waterhole::cp.groups.form', compact('form', 'group'));
    }

    public function update(Group $group, Request $request)
    {
        $this->form($group)->submit($request);

        return redirect()->route('waterhole.cp.groups.index');
    }

    private function form(Group $group)
    {
        return new GroupForm($group);
    }
}
