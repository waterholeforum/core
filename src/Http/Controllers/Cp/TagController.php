<?php

namespace Waterhole\Http\Controllers\Cp;

use HotwiredLaravel\TurboLaravel\Http\TurboResponseFactory;
use Illuminate\Http\Request;
use Waterhole\Forms\TagForm;
use Waterhole\Models\Tag;
use Waterhole\Models\Taxonomy;
use Waterhole\View\Components\Cp\TagRow;
use Waterhole\View\TurboStream;
use function Waterhole\internal_url;

class TagController
{
    public function create(Taxonomy $taxonomy)
    {
        $form = $this->form(new Tag(['icon' => 'emoji:']));

        return view('waterhole::cp.taxonomies.tag', compact('form', 'taxonomy'));
    }

    public function store(Taxonomy $taxonomy, Request $request)
    {
        $tag = new Tag();
        $tag->taxonomy()->associate($taxonomy);

        $this->form($tag)->submit($request);

        if ($request->wantsTurboStream()) {
            return TurboResponseFactory::makeStream(
                TurboStream::before(new TagRow($tag), '#tag-list-end'),
            );
        }

        return redirect($taxonomy->edit_url);
    }

    public function edit(Taxonomy $taxonomy, Tag $tag)
    {
        $form = $this->form($tag);

        return view('waterhole::cp.taxonomies.tag', compact('form', 'taxonomy', 'tag'));
    }

    public function update(Taxonomy $taxonomy, Tag $tag, Request $request)
    {
        $this->form($tag)->submit($request);

        if ($request->wantsTurboStream()) {
            return TurboResponseFactory::makeStream(TurboStream::replace(new TagRow($tag)));
        }

        return redirect(internal_url($request->input('return'), $taxonomy->edit_url))->with(
            'success',
            __('waterhole::cp.tag-saved-message'),
        );
    }

    private function form(Tag $tag)
    {
        return new TagForm($tag);
    }
}
