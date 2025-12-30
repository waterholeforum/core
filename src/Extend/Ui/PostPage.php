<?php

namespace Waterhole\Extend\Ui;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\View\Components\CommentsLocked;
use Waterhole\View\Components\PostAnswer;
use Waterhole\View\Components\PostAttribution;
use Waterhole\View\Components\PostChannel;
use Waterhole\View\Components\PostTagsSummary;
use Waterhole\View\Components\PostTitle;
use Waterhole\View\Components\PostTrash;

/**
 * Components rendered on the post show page.
 *
 * Use this extender to add, remove, or reorder components rendered in this
 * region of the UI.
 */
class PostPage
{
    public ComponentList $header;
    public ComponentList $sidebar;
    public ComponentList $middle;
    public ComponentList $bottom;

    public function __construct()
    {
        $this->header = (new ComponentList())
            ->add('trash', PostTrash::class)
            ->add('channel', PostChannel::class)
            ->add('tags', PostTagsSummary::class)
            ->add('attribution', PostAttribution::class)
            ->add('title', PostTitle::class);

        $this->sidebar = new ComponentList();

        $this->middle = (new ComponentList())->add('answer', PostAnswer::class);

        $this->bottom = (new ComponentList())->add('locked', CommentsLocked::class);
    }
}
