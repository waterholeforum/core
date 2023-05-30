<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\User;

class UserLastSeen extends Component
{
    public function __construct(public User $user)
    {
    }

    public function shouldRender()
    {
        return $this->user->show_online;
    }

    public function render()
    {
        return <<<'blade'
            @if ($user->isOnline())
                <span class="row gap-xxs color-success weight-medium">
                    <span class="dot"></span>
                    {{ __('waterhole::user.online-label') }}
                </span>
            @elseif ($user->last_seen_at)
                <span class="with-icon">
                    @icon('tabler-eye')
                    <span>
                        {{ __('waterhole::user.user-last-seen-text') }}
                        <x-waterhole::time-ago :datetime="$user->last_seen_at"/>
                    </span>
                </span>
            @endif
        blade;
    }
}
