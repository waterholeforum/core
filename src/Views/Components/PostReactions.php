<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostReactions extends Component
{
    public Post $post;
    public bool $interactive;

    public function __construct(Post $post, bool $interactive = false)
    {
        $this->post = $post;
        $this->interactive = $interactive;
    }

    // public function shouldRender()
    // {
    //     return $this->interactive;
    // }

    public function render()
    {
        return '<x-waterhole::reactions :model="$post"/>';
    }
}
