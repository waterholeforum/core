<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Comment;

class CommentFrame extends Component
{
    public Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function render()
    {
        return <<<'blade'
            <turbo-frame id="@domid($comment)">
                <x-waterhole::comment-full :comment="$comment"/>
            </turbo-frame>
        blade;
    }
}
