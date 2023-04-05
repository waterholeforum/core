<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostAnswer extends Component
{
    public function __construct(public Post $post)
    {
    }

    public function shouldRender(): bool
    {
        return $this->post->channel->answerable &&
            $this->post->answer &&
            $this->post->answer->index > 0;
    }

    public function render()
    {
        return $this->view('waterhole::components.post-answer');
    }
}
