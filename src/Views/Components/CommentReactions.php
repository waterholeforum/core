<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Comment;

class CommentReactions extends Component
{
    public function __construct(public Comment $comment)
    {
    }

    public function render()
    {
        return <<<'blade'
            <x-waterhole::reactions :model="$comment"/>
        blade;
    }
}
