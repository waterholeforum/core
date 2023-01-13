<?php

namespace Waterhole\Taxonomy;

use Illuminate\Http\Request;

class TaxonomyController
{
    public function index()
    {
        return view('waterhole::admin.taxonomies.index', [
            'taxonomies' => Taxonomy::withCount('tags')->get(),
        ]);
    }

    public function create()
    {
        $form = $this->form(new Taxonomy());

        return view('waterhole::admin.taxonomies.taxonomy', compact('form'));
    }

    public function store(Request $request)
    {
        $this->form($taxonomy = new Taxonomy())->submit($request);

        return redirect($taxonomy->edit_url);
    }

    public function edit(Taxonomy $taxonomy)
    {
        $form = $this->form($taxonomy);

        return view('waterhole::admin.taxonomies.taxonomy', compact('form', 'taxonomy'));
    }

    public function update(Taxonomy $taxonomy, Request $request)
    {
        $this->form($taxonomy)->submit($request);

        return redirect($request->input('return', route('waterhole.admin.taxonomies.index')))->with(
            'success',
            __('waterhole::admin.taxonomy-saved-message'),
        );
    }

    private function form(Taxonomy $taxonomy)
    {
        return new TaxonomyForm($taxonomy);
    }
}
