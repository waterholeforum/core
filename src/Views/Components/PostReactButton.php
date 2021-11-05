<?php

namespace Waterhole\Views\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostReactButton extends Component
{
    public Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function shouldRender()
    {
        return Auth::check();
    }

    public function render()
    {
        return <<<'blade'
            <x-waterhole::react-button :model="$post"/>
        blade;
    }
}
