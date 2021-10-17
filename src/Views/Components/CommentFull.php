<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Comment;

class CommentFull extends Component
{
    public Comment $comment;
    public bool $withReplies;

    public function __construct(Comment $comment, bool $withReplies = false)
    {
        $this->comment = $comment;
        $this->withReplies = $withReplies;
    }

    public function render()
    {
        return view('waterhole::components.comment-full');
    }
}
