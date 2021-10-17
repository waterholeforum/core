<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class SearchInput extends Component
{
    public string $placeholder;

    public function __construct(string $placeholder)
    {
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('waterhole::components.search-input');
    }
}
