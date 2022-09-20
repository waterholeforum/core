<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;
use Waterhole\Views\Components\Concerns\Streamable;

class PostListItem extends Component
{
    use Streamable;

    public function __construct(public Post $post)
    {
    }

    public function render()
    {
        return view('waterhole::components.post-list-item');
    }
}
