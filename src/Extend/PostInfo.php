<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\PostActivity;
use Waterhole\Views\Components\PostChannel;
use Waterhole\Views\Components\PostFollowing;
use Waterhole\Views\Components\PostUnread;

class PostInfo
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            PostUnread::class,
            PostChannel::class,
            PostActivity::class,
            PostFollowing::class,
        ];
    }
}
