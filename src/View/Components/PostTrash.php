<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostTrash extends Component
{
    public function __construct(public Post $post)
    {
    }

    public function shouldRender(): bool
    {
        return $this->post->trashed();
    }

    public function render()
    {
        return $this->view('waterhole::components.post-trash');
    }
}
