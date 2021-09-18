<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class PostFooter
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::post-likes',
            'waterhole::post-replies',
        ];
    }
}
