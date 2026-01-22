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
     * Users who can moderate a post can moderate its comments.
     */
    public function moderate(User $user, Comment $comment): bool
    {
        return $user->can('waterhole.post.moderate', $comment->post);
    }

    /**
     * Users can edit their own comments. Users who can moderate a post can
     * edit its comments.
     */
    public function edit(User $user, Comment $comment): bool
    {
        return !$comment->trashed() &&
            ($comment->user_id === $user->id || $this->moderate($user, $comment));
    }

    /**
     * Users can delete their own comments.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $comment->user_id === $user->id || $this->moderate($user, $comment);
    }

    /**
     * Any user can react to a comment.
     */
    public function react(User $user, Comment $comment): bool
    {
        return !$comment->trashed();
    }
}
