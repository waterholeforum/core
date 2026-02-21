<?php

namespace Waterhole\Forms\Fields;

use Waterhole\Forms\Field;
use Waterhole\Models\Taxonomy;

class TaxonomyTags extends Field
{
    public function __construct(public ?Taxonomy $model) {}

    public function shouldRender(): bool
    {
        return (bool) $this->model?->exists;
    }

    public function render(): string
    {
        return <<<'blade'
            <turbo-frame id="tags" data-action="turbo:frame-load->page#closeModal">
                <div class="stack gap-md">
                    <ul class="card" role="list">
                        @foreach ($model->tags->load('taxonomy') as $tag)
                            <x-waterhole::cp.tag-row :tag="$tag" />
                        @endforeach

                        <li class="placeholder hide-if-not-only-child" id="tag-list-end">
                            No Tags
                        </li>
                    </ul>

                    <div>
                        <a
                            href="{{ route('waterhole.cp.taxonomies.tags.create', ['taxonomy' => $model]) }}"
                            class="btn"
                            data-turbo-frame="modal"
                        >
                            @icon('tabler-plus')
                            Add
                        </a>
                    </div>
                </div>
            </turbo-frame>
        blade;
    }
}
