<?php

namespace Waterhole\Views\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Models\Comment;

class CommentButtonReact extends Component
{
    public Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function shouldRender()
    {
        return Auth::check();
    }

    public function render()
    {
        return view('waterhole::components.comment-button-react');
    }
}
