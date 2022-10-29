<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class Spinner extends Component
{
    public function render(): string
    {
        return <<<'blade'
            <div {{ $attributes
                ->class('spinner')
                ->merge(['role' => 'status', 'aria-label' => __('waterhole::system.loading')]) }}></div>
        blade;
    }
}
