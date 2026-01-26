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
        if ($comment->trashed()) {
            return false;
        }

        if ($this->moderate($user, $comment)) {
            return true;
        }

        if ($comment->user_id !== $user->id) {
            return false;
        }

        $limitMinutes = config('waterhole.forum.edit_time_limit');

        if ($limitMinutes === null) {
            return true;
        }

        return $comment->created_at->diffInMinutes(now()) <= $limitMinutes;
    }

    /**
     * Users can delete their own comments.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $comment->user_id === $user->id || $this->moderate($user, $comment);
    }

    /**
     * Users can restore their own deleted comments.
     */
    public function restore(User $user, Comment $comment): bool
    {
        return $this->moderate($user, $comment) ||
            ($comment->user_id === $user->id && $comment->deleted_by === $user->id);
    }

    /**
     * Any user can react to a comment.
     */
    public function react(User $user, Comment $comment): bool
    {
        return !$comment->trashed();
    }
}
