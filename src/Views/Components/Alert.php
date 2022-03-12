<?php

namespace Waterhole\Views\Components;

use Illuminate\Support\HtmlString;
use Illuminate\View\Component;

class Alert extends Component
{
    const ICONS = [
        'success' => 'heroicon-o-check',
        'danger' => 'heroicon-o-exclamation-circle',
    ];

    public ?string $type;
    public string|HtmlString $slot;
    public ?string $icon;
    public bool $dismissible;

    public function __construct(
        string $type = null,
        string|HtmlString $message = '',
        string $icon = null,
        bool $dismissible = false
    ) {
        $this->type = $type;
        $this->slot = $message;
        $this->icon = $icon ?? static::ICONS[$type] ?? null;
        $this->dismissible = $dismissible;
    }

    public function render()
    {
        return view('waterhole::components.alert');
    }
}
