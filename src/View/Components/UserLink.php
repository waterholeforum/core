<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\User;

class UserLink extends Component
{
    public function __construct(public ?User $user = null, public bool $link = true)
    {
    }

    public function render()
    {
        return $this->view('waterhole::components.user-link');
    }
}
