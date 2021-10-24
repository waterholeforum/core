<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostNotifications extends Component
{
    public Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function shouldRender()
    {
        return $this->post->userState?->notifications;
    }

    public function render()
    {
        return view('waterhole::components.post-notifications');
    }
}
