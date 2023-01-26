<?php

namespace Waterhole\View\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Models\User;

class UserGroups extends Component
{
    public Collection $groups;

    public function __construct(public User $user)
    {
        $this->groups = $this->user->groups->where('is_public', true);

        if (Auth::user()->can('user.edit', $user)) {
            $this->groups->push(...$this->user->groups->where('is_public', false));
        }
    }

    public function shouldRender(): bool
    {
        return $this->groups->isNotEmpty();
    }

    public function render()
    {
        return <<<'blade'
            <span>
                @foreach ($groups as $group)
                    <x-waterhole::group-badge :group="$group"/>
                @endforeach
            </span>
        blade;
    }
}
