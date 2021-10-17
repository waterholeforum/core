<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class ChannelPicker extends Component
{
    public string $name;
    public ?string $value;
    public array $exclude;
    public bool $allowNull;

    public function __construct(string $name, string $value = null, array $exclude = [], bool $allowNull = false)
    {
        $this->name = $name;
        $this->value = $value;
        $this->exclude = $exclude;
        $this->allowNull = $allowNull;
    }

    public function render()
    {
        return view('waterhole::components.channel-picker');
    }
}
