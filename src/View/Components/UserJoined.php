<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\User;

class UserJoined extends Component
{
    public function __construct(public User $user)
    {
    }

    public function shouldRender()
    {
        return $this->user->created_at;
    }

    public function render()
    {
        return <<<'blade'
            <span class="with-icon">
                @icon('tabler-calendar')
                <span>
                    {{ __('waterhole::user.user-joined-text') }}
                    <x-waterhole::time-ago :datetime="$user->created_at"/>
                </span>
            </span>
        blade;
    }
}
