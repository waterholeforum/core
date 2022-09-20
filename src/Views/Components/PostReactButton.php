<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostReactButton extends Component
{
    public function __construct(public Post $post)
    {
    }

    public function render()
    {
        return <<<'blade'
            <x-waterhole::react-button :model="$post"/>
        blade;
    }
}
