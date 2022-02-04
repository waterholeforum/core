<?php

namespace Waterhole\Views\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Models\Comment;
use Waterhole\Models\User;

class CommentReplyButton extends Component
{
    public Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function render(): View
    {
        return view('waterhole::components.comment-reply-button');
    }

    public function shouldRender(): bool
    {
        // If the user is logged in, only render the reply button if they can
        // post a comment. If they're a guest, only render the reply button if
        // a normal user would be able to post a comment.
        $user = Auth::user() ?: new User();

        return $user->can('post.comment', $this->comment->post);
    }
}
