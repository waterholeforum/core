<?php

namespace Waterhole\Extend\Ui;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\View\Components\FeedFilters;
use Waterhole\View\Components\FeedTopPeriod;
use Waterhole\View\Components\IndexCreatePost;
use Waterhole\View\Components\PostFeedChannel;
use Waterhole\View\Components\PostFeedPinned;
use Waterhole\View\Components\PostFeedToolbar;
use Waterhole\View\Components\Spacer;
use Waterhole\View\Components\TagsFilter;

/**
 * Header and toolbar components rendered with post feeds.
 *
 * Use this extender to add, remove, or reorder components rendered in this
 * region of the UI.
 */
class PostFeed
{
    public ComponentList $header;
    public ComponentList $toolbar;

    public function __construct()
    {
        $this->header = (new ComponentList())
            ->add('channel', PostFeedChannel::class)
            ->add('pinned', PostFeedPinned::class)
            ->add('toolbar', PostFeedToolbar::class);

        $this->toolbar = (new ComponentList())
            ->add('filters', FeedFilters::class)
            ->add('top-period', FeedTopPeriod::class)
            ->add('spacer', Spacer::class)
            ->add('tags', TagsFilter::class)
            ->add('create-post', IndexCreatePost::class);
    }
}
