<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Views\Components\Concerns\Streamable;

class Composer extends Component
{
    use Streamable;

    public Post $post;

    public ?Comment $parent;

    public function __construct(Post $post, Comment $parent = null)
    {
        $this->post = $post;
        $this->parent = $parent?->exists ? $parent : null;
    }

    public function render()
    {
        return view('waterhole::components.composer');
    }
}
