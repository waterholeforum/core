<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\User;

class UserLink extends Component
{
    public ?User $user;

    public function __construct(?User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('waterhole::components.user-link');
    }
}
