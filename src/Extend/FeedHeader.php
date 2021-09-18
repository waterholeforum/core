<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class FeedHeader
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::feed-channel',
            'waterhole::feed-toolbar',
        ];
    }
}
