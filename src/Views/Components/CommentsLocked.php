<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;
use Waterhole\Views\Components\Concerns\Streamable;

class CommentsLocked extends Component
{
    use Streamable;

    public Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function render()
    {
        return view('waterhole::components.comments-locked');
    }
}
