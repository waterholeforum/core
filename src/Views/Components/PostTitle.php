<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostTitle extends Component
{
    public function __construct(public Post $post)
    {
    }

    public function render()
    {
        return view('waterhole::components.post-title');
    }
}
