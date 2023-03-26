<?php

namespace Waterhole\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Waterhole\Forms\PageForm;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Page;

/**
 * Controller for CP page management (create and update).
 *
 * Deletion is handled by the DeleteStructure action.
 */
class PageController extends Controller
{
    public function create()
    {
        $form = $this->form(new Page());

        return view('waterhole::cp.structure.page', compact('form'));
    }

    public function store(Request $request)
    {
        $this->form(new Page())->submit($request);

        return redirect()->route('waterhole.cp.structure');
    }

    public function edit(Page $page)
    {
        $form = $this->form($page);

        return view('waterhole::cp.structure.page', compact('form', 'page'));
    }

    public function update(Page $page, Request $request)
    {
        $this->form($page)->submit($request);

        return redirect()->route('waterhole.cp.structure');
    }

    private function form(Page $page)
    {
        return new PageForm($page);
    }
}
