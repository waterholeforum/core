<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class FeedToolbar
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::feed-sort',
            'waterhole::spacer',
            'waterhole::feed-new-post',
        ];
    }
}
