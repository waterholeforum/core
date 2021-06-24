<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\User;

class UserProfile extends Component
{
    public function __construct(public User $user, public ?string $title = null)
    {
        $this->title = $title ?: $user->name;

        $user->loadCount('posts', 'comments');
    }

    public function render()
    {
        return view('waterhole::components.user-profile');
    }
}
