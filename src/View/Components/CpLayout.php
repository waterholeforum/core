<?php

declare(strict_types=1);

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class CpLayout extends Component
{
    public function __construct(
        public ?string $title = null,
        public array $assets = [],
    ) {}

    public function render()
    {
        return $this->view('waterhole::components.cp-layout');
    }
}
