<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Views\Components\PostReactionsCondensed;
use Waterhole\Views\Components\PostReplies;
use Waterhole\Views\Components\PostSummary;

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
