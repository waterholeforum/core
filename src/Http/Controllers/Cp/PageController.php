<?php

namespace Waterhole\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Waterhole\Forms\PageForm;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Http\Controllers\Cp\Concerns\CreatesStructureChildren;
use Waterhole\Models\Page;

use function Waterhole\internal_url;

/**
 * Controller for CP page management (create and update).
 *
 * Deletion is handled by the DeleteStructure action.
 */
class PageController extends Controller
{
    use CreatesStructureChildren;

    public function create(Request $request)
    {
        $this->structureParent($request);
        $form = $this->form(new Page());

        return view('waterhole::cp.structure.page', compact('form'));
    }

    public function store(Request $request)
    {
        $parent = $this->structureParent($request);
        $this->form($page = new Page())->submit($request);
        $this->appendToStructureParent($page->structure, $parent);

        return redirect(internal_url($request->input('return'), route('waterhole.cp.structure')));
    }

    public function edit(Page $page)
    {
        $form = $this->form($page);

        return view('waterhole::cp.structure.page', compact('form', 'page'));
    }

    public function update(Page $page, Request $request)
    {
        $this->form($page)->submit($request);

        return redirect(internal_url($request->input('return'), route('waterhole.cp.structure')));
    }

    private function form(Page $page)
    {
        return new PageForm($page);
    }
}
