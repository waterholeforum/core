<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class PostInfo
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::post-unread',
            'waterhole::post-channel',
            'waterhole::post-activity',
        ];
    }
}
