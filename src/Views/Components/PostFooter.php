<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostFooter extends Component
{
    public Post $post;
    public bool $interactive;

    public function __construct(Post $post, bool $interactive = false)
    {
        $this->post = $post;
        $this->interactive = $interactive;
    }

    public function render()
    {
        return view('waterhole::components.post-footer');
    }
}
