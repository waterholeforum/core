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

PostListItem::add(PostSummary::class, 0, 'summary');
PostListItem::add(PostReactionsCondensed::class, 0, 'reactions');
PostListItem::add(PostReplies::class, 0, 'replies');
