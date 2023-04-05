<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Models\PermissionCollection;

class HeaderSearch extends Component
{
    public function shouldRender(): bool
    {
        return Auth::check() ||
            app(PermissionCollection::class)
                ->guest()
                ->isNotEmpty();
    }

    public function render()
    {
        return $this->view('waterhole::components.header-search');
    }
}
