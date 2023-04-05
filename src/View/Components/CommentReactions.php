<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Comment;

class CommentReactions extends Component
{
    public function __construct(public Comment $comment)
    {
    }

    public function render()
    {
        return $this->view('waterhole::components.comment-reactions');
    }
}
