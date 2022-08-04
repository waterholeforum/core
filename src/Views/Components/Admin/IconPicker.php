<?php

namespace Waterhole\Views\Components\Admin;

use Illuminate\View\Component;

class IconPicker extends Component
{
    public ?string $name;

    public ?string $id;

    public null|string|array $value;

    public ?string $type = null;

    public ?string $content = null;

    public function __construct(string $name = null, string $id = null, string|array $value = null)
    {
        $this->name = $name;
        $this->id = $id;
        $this->value = $value;

        if (is_array($value)) {
            $this->type = $value['type'] ?? null;
            $this->content = $value[$this->type] ?? null;
        } elseif ($value) {
            [$this->type, $this->content] = explode(':', $value, 2) + [null, null];
        }
    }

    public function render()
    {
        return view('waterhole::components.admin.icon-picker');
    }
}
