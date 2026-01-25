<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostAttribution extends Component
{
    public function __construct(public Post $post) {}

    public function render()
    {
        return $this->view('waterhole::components.post-attribution');
    }
}
