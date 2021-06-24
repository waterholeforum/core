<?php

namespace Waterhole\View\Components;

use Illuminate\Support\HtmlString;
use Illuminate\View\Component;

class MenuDivider extends Component
{
    public function render()
    {
        return new HtmlString('<hr class="menu-divider">');
    }
}
