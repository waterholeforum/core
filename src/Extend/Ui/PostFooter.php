<?php

namespace Waterhole\Extend\Ui;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\View\Components\PostReactions;
use Waterhole\View\Components\PostReplies;

/**
 * Components rendered in a post footer.
 *
 * Use this extender to add, remove, or reorder components rendered in this
 * region of the UI.
 */
class PostFooter extends ComponentList
{
    public function __construct()
    {
        $this->add('reactions', PostReactions::class);
        $this->add('replies', PostReplies::class);
    }
}
