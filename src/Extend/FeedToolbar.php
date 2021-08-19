<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class FeedToolbar
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::components.feed.sort',
            'waterhole::components.ui.spacer',
            'waterhole::components.feed.new-post',
        ];
    }
}
