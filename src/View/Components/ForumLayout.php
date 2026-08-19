<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Structure;

class ForumLayout extends Component
{
    public function __construct(
        public ?string $title = null,
        public array $assets = [],
        public array $seo = [],
        public ?Structure $activeNode = null,
        public bool $showSidebar = false,
    ) {}

    public function render()
    {
        return $this->view('waterhole::components.forum-layout');
    }
}
