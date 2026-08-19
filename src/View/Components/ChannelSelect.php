<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Channel;

class ChannelSelect extends Component
{
    public function __construct(
        public string $name,
        public ?Channel $channel = null,
        public array $exclude = [],
        public bool $showLinks = false,
        public ?string $form = null,
    ) {}

    public function render()
    {
        return $this->view('waterhole::components.channel-select');
    }
}
