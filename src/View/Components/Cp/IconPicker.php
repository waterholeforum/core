<?php

namespace Waterhole\View\Components\Cp;

use Illuminate\View\Component;

class IconPicker extends Component
{
    public ?string $type = null;
    public ?string $content = null;
    public ?string $color = null;

    public function __construct(
        public ?string $name = null,
        public ?string $id = null,
        public string|array|null $value = null,
    ) {
        if (is_array($value)) {
            $this->type = $value['type'] ?? null;
            $this->content = $value[$this->type] ?? null;
            $this->color = $value['color'] ?? null;
        } elseif ($value) {
            [$this->type, $this->content] = explode(':', $value, 2) + [null, null];

            if (
                $this->type === 'svg'
                && $this->content
                && preg_match(
                    '/^(.+):([a-f0-9]{3}|[a-f0-9]{4}|[a-f0-9]{6}|[a-f0-9]{8})$/i',
                    $this->content,
                    $matches,
                )
            ) {
                $this->content = $matches[1];
                $this->color = $matches[2];
            }
        }
    }

    public function render()
    {
        return $this->view('waterhole::components.cp.icon-picker');
    }

    public static function validationRules(): array
    {
        return [
            'icon' => ['array:type,emoji,svg,file,color'],
            'icon.type' => ['nullable', 'in:emoji,svg,file'],
            'icon.file' => ['nullable', 'image:allow_svg'],
            'icon.color' => [
                'nullable',
                'string',
                'regex:/^(?:[a-f0-9]{3}|[a-f0-9]{4}|[a-f0-9]{6}|[a-f0-9]{8})$/i',
            ],
        ];
    }
}
