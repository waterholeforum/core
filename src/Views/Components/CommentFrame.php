<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Comment;

class CommentFrame extends Component
{
    public function __construct(public Comment $comment, public bool $withReplies = false)
    {
    }

    public function render()
    {
        return <<<'blade'
            <turbo-frame id="@domid($comment)">
                <x-waterhole::comment-full :comment="$comment" :with-replies="$withReplies"/>
            </turbo-frame>
        blade;
    }
}
