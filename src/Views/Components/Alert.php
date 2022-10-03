<?php

namespace Waterhole\Views\Components;

use Illuminate\Support\HtmlString;
use Illuminate\View\Component;

class Alert extends Component
{
    public const ICONS = [
        'success' => 'tabler-check',
        'danger' => 'tabler-alert-circle',
    ];

    public function __construct(
        public ?string $type = null,
        public null|string|HtmlString $message = null,
        public ?string $icon = null,
        public bool $dismissible = false,
    ) {
        $this->icon = $icon ?? (static::ICONS[$type] ?? null);
    }

    public function render()
    {
        return view('waterhole::components.alert');
    }
}
