<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostNotifications extends Component
{
    public function __construct(public Post $post) {}

    public function shouldRender()
    {
        return $this->post->userState?->notifications;
    }

    public function render()
    {
        return $this->view('waterhole::components.post-notifications');
    }
}
