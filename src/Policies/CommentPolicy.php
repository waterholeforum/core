<?php

namespace Waterhole\Policies;

use Waterhole\Models\Comment;
use Waterhole\Models\User;

class CommentPolicy
{
    /**
     * Any user can create a comment.
     */
    public function create(): bool
    {
        return true;
    }

    /**
     * Users can edit their own comments. Users who can moderate a post can
     * edit its comments.
     */
    public function edit(User $user, Comment $comment): bool
    {
        return $comment->user_id === $user->id || $this->moderate($user, $comment);
    }

    /**
     * Users who can moderate a post can moderate its comments.
     */
    public function moderate(User $user, Comment $comment): bool
    {
        return $user->can('post.moderate', $comment->post);
    }

    /**
     * Any user can react to a comment.
     */
    public function react(): bool
    {
        return true;
    }
}
