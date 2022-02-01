<?php

namespace Waterhole\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Page;

/**
 * Controller for admin page management (create and update).
 *
 * Deletion is handled by the DeleteStructure action.
 */
class PageController extends Controller
{
    public function create()
    {
        return view('waterhole::admin.structure.page');
    }

    public function store(Request $request)
    {
        return $this->save(new Page(), $request);
    }

    public function edit(Page $page)
    {
        return view('waterhole::admin.structure.page', compact('page'));
    }

    public function update(Page $page, Request $request)
    {
        return $this->save($page, $request);
    }

    private function save(Page $page, Request $request)
    {
        $data = $request->validate(Page::rules($page));

        $icon = Arr::pull($data, 'icon');
        $permissions = Arr::pull($data, 'permissions');

        DB::transaction(function () use ($page, $data, $permissions, $icon) {
            $page->fill($data)->save();
            $page->saveIcon($icon);
            $page->savePermissions($permissions);
        });

        return redirect()->route('waterhole.admin.structure');
    }
}
