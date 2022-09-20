<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostReactions extends Component
{
    public function __construct(public Post $post)
    {
    }

    public function render()
    {
        return <<<'blade'
            <x-waterhole::reactions :model="$post"/>
        blade;
    }
}
