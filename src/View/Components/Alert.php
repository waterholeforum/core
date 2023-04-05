<?php

namespace Waterhole\View\Components;

use Illuminate\Support\HtmlString;
use Illuminate\View\Component;

class Alert extends Component
{
    public const ICONS = [
        'success' => 'tabler-check',
        'warning' => 'tabler-alert-triangle',
        'danger' => 'tabler-alert-circle',
    ];

    public function __construct(
        public ?string $type = null,
        public null|string|HtmlString $message = null,
        public ?string $icon = null,
        public bool $dismissible = false,
    ) {
        $this->icon = $icon ?? (static::ICONS[explode('-', $type)[0]] ?? null);
    }

    public function render()
    {
        return $this->view('waterhole::components.alert');
    }
}
