<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\View\ComponentAttributeBag;

abstract class Link extends Action
{
    abstract public function link($item);

    public function render(Collection $items, ComponentAttributeBag $attributes): HtmlString|null
    {
        if (! $this->visible($items)) {
            return null;
        }

        $link = e($this->link($items[0]));

        $attributes = new ComponentAttributeBag(array_merge(
            $this->attributes($items),
            $attributes->getAttributes()
        ));

        $content = $this->renderContent($items);

        return new HtmlString(<<<html
            <a href="$link" $attributes>$content</a>
        html);
    }
}
