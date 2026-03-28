<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Ui\KeyboardShortcut;

class ShortcutLabel extends Component
{
    public ?string $shortcut;

    public function __construct(KeyboardShortcut|string|null $shortcut = null)
    {
        $this->shortcut = $shortcut instanceof KeyboardShortcut ? $shortcut->id : $shortcut;
    }

    public function shouldRender(): bool
    {
        return (bool) $this->shortcut;
    }

    public function render()
    {
        return $this->view('waterhole::components.shortcut-label');
    }
}
