<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\FeedChannel;
use Waterhole\Views\Components\FeedToolbar;
use Waterhole\Views\Components\FeedTopPeriod;

class FeedHeader
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            FeedChannel::class,
            FeedToolbar::class,
        ];
    }
}
