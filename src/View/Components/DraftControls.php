<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class DraftControls extends Component
{
    public const FRAME = 'draft-controls';

    public string $frame = self::FRAME;

    public function __construct(public bool $saved = false, public string $action = '') {}

    public function render()
    {
        return $this->view('waterhole::components.draft-controls');
    }
}
