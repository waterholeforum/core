<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\User;

class UserLabel extends Component
{
    public ?User $user;
    public bool $link;

    public function __construct(?User $user, bool $link = false)
    {
        $this->user = $user;
        $this->link = $link;
    }

    public function render()
    {
        return view('waterhole::components.user-label');
    }
}
