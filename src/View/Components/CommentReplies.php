<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Comment;

class CommentReplies extends Component
{
    public function __construct(public Comment $comment, public bool $withReplies = false)
    {
    }

    public function render()
    {
        return $this->view('waterhole::components.comment-replies');
    }
}
