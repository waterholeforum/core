<?php

namespace Waterhole\View\Components\Admin;

use Illuminate\View\Component;
use Waterhole\Taxonomy\Tag;
use Waterhole\View\Components\Concerns\Streamable;

class TagRow extends Component
{
    use Streamable;

    public function __construct(public Tag $tag)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <li {{ $attributes->class('card__row row gap-sm') }}>
                {{ Waterhole\emojify($tag->name) }}

                <x-waterhole::action-buttons
                    :for="$tag"
                    :button-attributes="['class' => 'btn btn--icon btn--transparent']"
                    icons
                    class="push-end row -m-sm text-xs"
                />
            </li>
        blade;
    }
}
