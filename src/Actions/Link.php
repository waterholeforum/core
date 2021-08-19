<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\View\ComponentAttributeBag;

abstract class Link extends Action
{
    public bool $bulk = false;

    abstract public function link($item);

    public function render(Collection $items): HtmlString
    {
        $link = $this->link($items[0]);
        $attributes = new ComponentAttributeBag($this->attributes());
        $label = $this->label($items);

        return new HtmlString('<a href="'.e($link).'" '.$attributes.'>'.e($label).'</a>');
    }
}
