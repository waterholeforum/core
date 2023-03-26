<?php

namespace Waterhole\View\Components\Cp;

use Illuminate\View\Component;

class IconPicker extends Component
{
    public ?string $type = null;
    public ?string $content = null;

    public function __construct(
        public ?string $name = null,
        public ?string $id = null,
        public null|string|array $value = null,
    ) {
        if (is_array($value)) {
            $this->type = $value['type'] ?? null;
            $this->content = $value[$this->type] ?? null;
        } elseif ($value) {
            [$this->type, $this->content] = explode(':', $value, 2) + [null, null];
        }
    }

    public function render()
    {
        return view('waterhole::components.cp.icon-picker');
    }

    public static function validationRules(): array
    {
        return [
            'icon' => ['array:type,emoji,svg,file'],
            'icon.type' => ['nullable', 'in:emoji,svg,file'],
            'icon.file' => ['nullable', 'image'],
        ];
    }
}
