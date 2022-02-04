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
        return $comment->user_id === $user->id
            || $user->can('post.moderate', $comment->post);
    }

    /**
     * Users can delete their own comments. Users who can moderate a post can
     * delete its comments.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $this->edit($user, $comment);
    }

    /**
     * Any user can like a comment.
     */
    public function like(): bool
    {
        return true;
    }
}
