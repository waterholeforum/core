<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class PostInfo
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::components.post.info.channel',
            'waterhole::components.post.info.activity',
            'waterhole::components.post.info.unread',
        ];
    }
}
