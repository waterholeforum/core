<?php

namespace Waterhole\Http\Controllers\Cp;

use function Waterhole\internal_url;

use Illuminate\Http\Request;
use Waterhole\Forms\TaxonomyForm;
use Waterhole\Models\Taxonomy;

class TaxonomyController
{
    public function index()
    {
        return view('waterhole::cp.taxonomies.index', [
            'taxonomies' => Taxonomy::withCount('tags')->get(),
        ]);
    }

    public function create()
    {
        $form = $this->form(new Taxonomy());

        return view('waterhole::cp.taxonomies.taxonomy', compact('form'));
    }

    public function store(Request $request)
    {
        $this->form($taxonomy = new Taxonomy())->submit($request);

        return redirect($taxonomy->edit_url);
    }

    public function edit(Taxonomy $taxonomy)
    {
        $form = $this->form($taxonomy);

        return view('waterhole::cp.taxonomies.taxonomy', compact('form', 'taxonomy'));
    }

    public function update(Taxonomy $taxonomy, Request $request)
    {
        $this->form($taxonomy)->submit($request);

        return redirect(
            internal_url(
                $request->input('return'),
                route('waterhole.cp.taxonomies.index'),
            ),
        )->with(
            'success',
            __('waterhole::cp.taxonomy-saved-message'),
        );
    }

    private function form(Taxonomy $taxonomy)
    {
        return new TaxonomyForm($taxonomy);
    }
}
