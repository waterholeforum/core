<?php

namespace Waterhole\Extend\Ui;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\View\Components\PostActivity;
use Waterhole\View\Components\PostAnswered;
use Waterhole\View\Components\PostChannel;
use Waterhole\View\Components\PostLocked;
use Waterhole\View\Components\PostNotifications;
use Waterhole\View\Components\PostReactionsCondensed;
use Waterhole\View\Components\PostReplies;
use Waterhole\View\Components\PostSaved;
use Waterhole\View\Components\PostTagsSummary;
use Waterhole\View\Components\PostTrash;
use Waterhole\View\Components\PostUnread;

/**
 * Components rendered for posts in the list layout.
 *
 * Use this extender to add, remove, or reorder components rendered in this
 * region of the UI.
 */
class PostListItem
{
    public ComponentList $info;
    public ComponentList $secondary;

    public function __construct()
    {
        $this->info = (new ComponentList())
            ->add(PostUnread::class, 'unread')
            ->add(PostTrash::class, 'trash')
            ->add(PostChannel::class, 'channel')
            ->add(PostAnswered::class, 'answered')
            ->add(PostLocked::class, 'locked')
            ->add(PostNotifications::class, 'notifications')
            ->add(PostSaved::class, 'saved')
            ->add(PostActivity::class, 'activity');

        $this->secondary = (new ComponentList())
            ->add(PostTagsSummary::class, 'tags')
            ->add(PostReactionsCondensed::class, 'reactions')
            ->add(PostReplies::class, 'replies');
    }
}
