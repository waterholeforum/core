<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostFollowing extends Component
{
    public Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function shouldRender()
    {
        return $this->post->userState?->followed_at;
    }

    public function render()
    {
        return view('waterhole::components.post-following');
    }
}
