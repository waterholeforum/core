<?php

namespace Waterhole\Views\Components\Admin;

use Illuminate\View\Component;

class ColorPicker extends Component
{
    public ?string $name;

    public ?string $id;

    public ?string $value;

    public function __construct(string $name = null, string $id = null, string $value = null)
    {
        $this->name = $name;
        $this->id = $id;
        $this->value = $value;
    }

    public function render()
    {
        return view('waterhole::components.admin.color-picker');
    }
}
