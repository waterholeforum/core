<?php

namespace Waterhole\Extend\Ui;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Waterhole\Extend\Support\Attributes;
use Waterhole\Models\Comment;

/**
 * HTML attributes applied to comment wrappers.
 *
 * Use this extender to add, remove, or reorder components rendered in this
 * region of the UI.
 */
class CommentAttributes extends Attributes
{
    public function __construct()
    {
        $this->add(
            fn(Comment $comment) => [
                'class' => Arr::toCssClasses([
                    'is-unread' => $comment->isUnread(),
                    'is-read' => $comment->isRead(),
                    'is-mine' => $comment->user_id === Auth::id(),
                    'is-answer' => $comment->isAnswer(),
                    'has-replies' => $comment->reply_count,
                    'is-removed' => $comment->trashed(),
                ]),
            ],
        );
    }
}
