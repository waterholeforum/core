<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\User;

class UserLabel extends Component
{
    public function __construct(public ?User $user = null, public bool $link = false)
    {
    }

    public function render()
    {
        return view('waterhole::components.user-label');
    }
}
