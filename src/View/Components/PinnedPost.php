<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;
use Waterhole\View\Components\Concerns\Streamable;

class PinnedPost extends Component
{
    use Streamable;

    public function __construct(public Post $post)
    {
    }

    public function render()
    {
        return $this->view('waterhole::components.pinned-post');
    }
}
