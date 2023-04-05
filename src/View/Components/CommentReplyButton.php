<?php

namespace Waterhole\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Models\Comment;
use Waterhole\Models\User;

class CommentReplyButton extends Component
{
    public function __construct(public Comment $comment)
    {
    }

    public function shouldRender(): bool
    {
        // If the user is logged in, only render the reply button if they can
        // post a comment. If they're a guest, only render the reply button if
        // a normal user would be able to post a comment.
        $user = Auth::user() ?: new User();

        return $user->can('post.comment', $this->comment->post);
    }

    public function render(): View
    {
        return $this->view('waterhole::components.comment-reply-button');
    }
}
