<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class PostHeader
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::post-channel',
            'waterhole::post-title',
            'waterhole::post-attribution',
        ];
    }
}
