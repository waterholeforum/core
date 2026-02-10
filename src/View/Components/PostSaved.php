<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostSaved extends Component
{
    public function __construct(public Post $post) {}

    public function shouldRender(): bool
    {
        return $this->post->isBookmarked();
    }

    public function render()
    {
        return $this->view('waterhole::components.post-saved');
    }
}
