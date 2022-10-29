<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\PostReactionsCondensed;
use Waterhole\View\Components\PostReplies;
use Waterhole\View\Components\PostSummary;

/**
 * A list of components to render for each post in the "list" layout.
 */
abstract class PostListItem
{
    use OrderedList;
}

PostListItem::add('summary', PostSummary::class);
PostListItem::add('reactions', PostReactionsCondensed::class);
PostListItem::add('replies', PostReplies::class);
