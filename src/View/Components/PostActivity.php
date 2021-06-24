<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostActivity extends Component
{
    public function __construct(public Post $post)
    {
    }

    public function render()
    {
        return view('waterhole::components.post-activity');
    }
}
