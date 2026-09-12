<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class Layout extends Component
{
    public function __construct(
        public ?string $title = null,
        public array $assets = [],
        public array $seo = [],
        public bool $globalSidebar = false,
        public bool $showSidebar = true,
        public ?string $rss = null,
    ) {}

    public function render()
    {
        return $this->view('waterhole::components.layout');
    }
}
