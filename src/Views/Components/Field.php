<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class Field extends Component
{
    public string $name;
    public string $label;
    public ?string $description;
    public string $id;

    public function __construct(string $name, string $label, string $description = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->description = $description;

        $this->id = $name;
    }

    public function render()
    {
        return view('waterhole::components.field');
    }
}
