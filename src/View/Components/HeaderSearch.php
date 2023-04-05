<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Waterhole;

class HeaderSearch extends Component
{
    public function shouldRender(): bool
    {
        return Auth::check() || Waterhole::permissions()->can(null, 'view', Channel::class);
    }

    public function render()
    {
        return $this->view('waterhole::components.header-search');
    }
}
