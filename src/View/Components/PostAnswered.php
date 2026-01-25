<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostAnswered extends Component
{
    public function __construct(public Post $post) {}

    public function shouldRender(): bool
    {
        return $this->post->channel->answerable && $this->post->answer_id;
    }

    public function render()
    {
        return $this->view('waterhole::components.post-answered');
    }
}
