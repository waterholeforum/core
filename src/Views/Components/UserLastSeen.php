<?php

namespace Waterhole\Views\Components;

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
            @else
            <span class="with-icon">
                <x-waterhole::icon icon="tabler-eye"/>
                <span>{{ __('waterhole::user.user-last-seen-text', ['date' => $user->last_seen_at->isoFormat('MMM YYYY')]) }}</span>
            </span>
            @endif
        blade;
    }
}
