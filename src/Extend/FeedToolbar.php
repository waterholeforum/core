<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\FeedNewPost;
use Waterhole\Views\Components\FeedSort;
use Waterhole\Views\Components\Spacer;

class FeedToolbar
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            FeedSort::class,
            Spacer::class,
            FeedNewPost::class,
        ];
    }
}
