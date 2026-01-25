<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostReactionsCondensed extends Component
{
    public function __construct(public Post $post) {}

    public function render()
    {
        return <<<'blade'
            <x-waterhole::reactions-condensed :model="$post"/>
        blade;
    }
}
