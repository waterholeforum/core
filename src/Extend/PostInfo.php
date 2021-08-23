<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class PostInfo
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::posts.info.unread',
            'waterhole::post-channel',
            'waterhole::posts.info.activity',
        ];
    }
}
