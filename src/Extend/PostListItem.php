<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\PostReactionsCondensed;
use Waterhole\Views\Components\PostReplies;
use Waterhole\Views\Components\PostSummary;

class PostListItem
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            PostSummary::class,
            PostReactionsCondensed::class,
            PostReplies::class,
        ];
    }
}
