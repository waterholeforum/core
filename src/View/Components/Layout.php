<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class Layout extends Component
{
    public function __construct(
        public ?string $title = null,
        public array $assets = [],
        public array $seo = [],
    )
    {
    }

    public function render()
    {
        return $this->view('waterhole::components.layout');
    }
}
