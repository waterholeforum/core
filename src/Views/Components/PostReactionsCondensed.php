<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostReactionsCondensed extends Component
{
    public Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function render()
    {
        return <<<'blade'
            <x-waterhole::reactions-condensed :model="$post"/>
        blade;
    }
}
