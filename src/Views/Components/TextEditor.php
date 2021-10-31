<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class TextEditor extends Component
{
    public string $name;
    public ?string $id;
    public ?string $value;

    public function __construct(string $name, string $id = null, string $value = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->id = $id ?: 'text-editor-'.uniqid();
    }

    public function render()
    {
        return view('waterhole::components.text-editor');
    }
}
