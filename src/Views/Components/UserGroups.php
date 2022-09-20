<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\User;

class UserGroups extends Component
{
    public function __construct(public User $user)
    {
    }

    public function shouldRender()
    {
        return $this->user->groups->where('is_public')->count();
    }

    public function render()
    {
        return <<<'blade'
            <span>
                @foreach ($user->groups->filter->is_public as $group)
                    <x-waterhole::group-label :group="$group"/>
                @endforeach
            </span>
        blade;
    }
}
