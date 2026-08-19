<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\View\Components\Concerns\Streamable;

class StructureHeader extends Component
{
    use Streamable;

    public function __construct(
        public Channel|Page $content,
    ) {}

    public function render()
    {
        return $this->view('waterhole::components.structure-header');
    }
}
