<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class Dialog extends Component
{
    public ?string $title;

    public function __construct(string $title = null)
    {
        $this->title = $title;
    }

    public function render()
    {
        return view('waterhole::components.dialog');
    }
}
