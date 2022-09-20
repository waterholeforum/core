<?php

namespace Waterhole\Views\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextEditorButton extends Component
{
    public function __construct(
        public string $id,
        public string $icon,
        public string $label,
        public ?string $format = null,
        public ?string $hotkey = null,
    ) {
    }

    public function render(): View
    {
        return view('waterhole::components.text-editor-button');
    }
}
