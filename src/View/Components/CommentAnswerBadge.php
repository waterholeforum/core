<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Comment;

class CommentAnswerBadge extends Component
{
    public function __construct(public Comment $comment)
    {
    }

    public function shouldRender(): bool
    {
        return $this->comment->post->channel->answerable && $this->comment->isAnswer();
    }

    public function render()
    {
        return $this->view('waterhole::components.comment-answer-badge');
    }
}
