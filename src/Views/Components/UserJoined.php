<?php

namespace Waterhole\Views\Components;

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
                <x-waterhole::icon icon="tabler-calendar"/>
                <span>{{ __('waterhole::user.user-joined-text', ['date' => $user->created_at->isoFormat('MMM YYYY')]) }}</span>
            </span>
        blade;
    }
}
