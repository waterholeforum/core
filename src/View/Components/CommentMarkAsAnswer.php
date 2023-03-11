<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Comment;

class CommentMarkAsAnswer extends Component
{
    public function __construct(public Comment $comment)
    {
    }

    public function shouldRender(): bool
    {
        return $this->comment->post->channel->answerable;
    }

    public function render()
    {
        return view('waterhole::components.comment-mark-as-answer');
    }
}
