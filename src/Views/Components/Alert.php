<?php

namespace Waterhole\Views\Components;

use Illuminate\Support\HtmlString;
use Illuminate\View\Component;

class Alert extends Component
{
    public const ICONS = [
        'success' => 'heroicon-o-check',
        'danger' => 'heroicon-o-exclamation-circle',
    ];

    public string|HtmlString $slot;

    public function __construct(
        public ?string $type = null,
        public string|HtmlString $message = '',
        public ?string $icon = null,
        public bool $dismissible = false,
    ) {
        $this->slot = $message;
        $this->icon = $icon ?? (static::ICONS[$type] ?? null);
    }

    public function render()
    {
        return view('waterhole::components.alert');
    }
}
