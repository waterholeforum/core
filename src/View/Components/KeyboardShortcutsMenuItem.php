<?php

declare(strict_types=1);

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class KeyboardShortcutsMenuItem extends Component
{
    public function render()
    {
        return $this->view('waterhole::components.keyboard-shortcuts-menu-item');
    }
}
