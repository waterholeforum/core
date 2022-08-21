<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Comment;
use Waterhole\Views\Components\Concerns\Streamable;

class CommentFull extends Component
{
    use Streamable;

    public function __construct(public Comment $comment, public bool $withReplies = false)
    {
    }

    public function render()
    {
        return view('waterhole::components.comment-full');
    }
}
