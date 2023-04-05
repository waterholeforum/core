<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\User;

class UserLocation extends Component
{
    public function __construct(public User $user)
    {
    }

    public function shouldRender()
    {
        return $this->user->location;
    }

    public function render()
    {
        return <<<'blade'
            <span class="with-icon">
                @icon('tabler-map-pin')
                <span>{{ $user->location }}</span>
            </span>
        blade;
    }
}
