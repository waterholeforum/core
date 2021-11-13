<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\User;

class UserProfile extends Component
{
    public User $user;
    public string $title;

    public function __construct(User $user, string $title = null)
    {
        $this->user = $user;
        $this->title = $title ?: $user->name;

        $user->loadCount('posts', 'comments');
    }

    public function render()
    {
        return view('waterhole::components.user-profile');
    }
}
