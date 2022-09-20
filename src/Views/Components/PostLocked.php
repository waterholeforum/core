<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostLocked extends Component
{
    public function __construct(public Post $post)
    {
    }

    public function shouldRender()
    {
        return $this->post->is_locked;
    }

    public function render()
    {
        return view('waterhole::components.post-locked');
    }
}
