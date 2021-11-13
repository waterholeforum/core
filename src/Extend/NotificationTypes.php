<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Notifications\Mention;
use Waterhole\Notifications\NewComment;
use Waterhole\Notifications\NewPost;

class NotificationTypes
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            NewPost::class,
            NewComment::class,
            Mention::class,
        ];
    }
}
