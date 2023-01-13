<?php

namespace Waterhole\Taxonomy;

use Illuminate\Http\Request;

class TagController
{
    public function create(Taxonomy $taxonomy)
    {
        $form = $this->form(new Tag(['icon' => 'emoji:']));

        return view('waterhole::admin.taxonomies.tag', compact('form', 'taxonomy'));
    }

    public function store(Taxonomy $taxonomy, Request $request)
    {
        $tag = new Tag();
        $tag->taxonomy()->associate($taxonomy);

        $this->form($tag)->submit($request);

        return redirect($taxonomy->edit_url);
    }

    public function edit(Taxonomy $taxonomy, Tag $tag)
    {
        $form = $this->form($tag);

        return view('waterhole::admin.taxonomies.tag', compact('form', 'taxonomy', 'tag'));
    }

    public function update(Taxonomy $taxonomy, Tag $tag, Request $request)
    {
        $this->form($tag)->submit($request);

        return redirect($request->input('return', $taxonomy->edit_url))->with(
            'success',
            __('waterhole::admin.tag-saved-message'),
        );
    }

    private function form(Tag $tag)
    {
        return new TagForm($tag);
    }
}
