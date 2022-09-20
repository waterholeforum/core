<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class TextEditor extends Component
{
    public function __construct(
        public string $name,
        public ?string $id = null,
        public ?string $value = null,
        public ?string $placeholder = null,
    ) {
        $this->id = $id ?: 'text-editor-' . uniqid();
    }

    public function render()
    {
        return view('waterhole::components.text-editor');
    }
}
