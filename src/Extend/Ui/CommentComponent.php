<?php

namespace Waterhole\Extend\Ui;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\View\Components\CommentAnswerBadge;
use Waterhole\View\Components\CommentMarkAsAnswer;
use Waterhole\View\Components\CommentReactions;
use Waterhole\View\Components\CommentReplies;
use Waterhole\View\Components\CommentReplyButton;

/**
 * Components rendered in comment headers, footers, and buttons.
 *
 * Use this extender to add, remove, or reorder components rendered in this
 * region of the UI.
 */
class CommentComponent
{
    public ComponentList $header;
    public ComponentList $footer;
    public ComponentList $buttons;

    public function __construct()
    {
        $this->header = (new ComponentList())->add(CommentAnswerBadge::class, 'answer');

        $this->footer = (new ComponentList())
            ->add(CommentReactions::class, 'reactions')
            ->add(CommentReplies::class, 'replies');

        $this->buttons = (new ComponentList())
            ->add(CommentMarkAsAnswer::class, 'answer')
            ->add(CommentReplyButton::class, 'reply');
    }
}
