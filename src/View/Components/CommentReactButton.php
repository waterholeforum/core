<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Models\Comment;

class CommentReactButton extends Component
{
    public function __construct(public Comment $comment)
    {
    }

    public function shouldRender()
    {
        return Auth::check();
    }

    public function render()
    {
        return <<<'blade'
            <x-waterhole::react-button :model="$comment"/>
        blade;
    }
}
