<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class TextEditor extends Component
{
    public function __construct(
        public string $name,
        public ?string $id = null,
        public ?string $value = null,
        public ?string $placeholder = null,
        public ?string $userLookupUrl = null,
    ) {
        $this->id = $id ?: 'text-editor-' . uniqid();
        $this->userLookupUrl ??= route('waterhole.user-lookup');
    }

    public function render()
    {
        return $this->view('waterhole::components.text-editor');
    }
}
