<?php

namespace Waterhole\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextEditorButton extends Component
{
    public function __construct(
        public string $icon,
        public string $label,
        public ?string $id = null,
        public ?string $format = null,
        public ?string $hotkey = null,
    ) {
    }

    public function render(): View
    {
        return $this->view('waterhole::components.text-editor-button');
    }
}
