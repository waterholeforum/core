<?php

namespace Waterhole\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Page;

class PageController extends Controller
{
    public function create()
    {
        return view('waterhole::admin.structure.page');
    }

    public function store(Request $request)
    {
        $data = $request->validate(Page::rules());
        $permissions = Arr::pull($data, 'permissions');
        $icon = Arr::pull($data, 'icon');

        $page = new Page($data);
        $page->save();
        $page->savePermissions($permissions);
        $page->saveIcon($icon);

        return redirect()->route('waterhole.admin.structure');
    }

    public function edit(Page $page)
    {
        return view('waterhole::admin.structure.page', compact('page'));
    }

    public function update(Page $page, Request $request)
    {
        $data = $request->validate(Page::rules($page));
        $permissions = Arr::pull($data, 'permissions');
        $icon = Arr::pull($data, 'icon');

        $page->update($data);
        $page->savePermissions($permissions);
        $page->saveIcon($icon);

        return redirect()->route('waterhole.admin.structure');
    }
}
