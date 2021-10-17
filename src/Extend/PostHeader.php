<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\PostAttribution;
use Waterhole\Views\Components\PostChannel;
use Waterhole\Views\Components\PostTitle;

class PostHeader
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            PostChannel::class,
            PostTitle::class,
            PostAttribution::class,
        ];
    }
}
