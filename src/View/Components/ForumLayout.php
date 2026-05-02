<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Channel;

class ForumLayout extends Component
{
    public function __construct(
        public ?string $title = null,
        public array $assets = [],
        public array $seo = [],
        public ?Channel $channel = null,
        public bool $showSidebar = false,
    ) {}

    public function render()
    {
        return $this->view('waterhole::components.forum-layout');
    }
}
