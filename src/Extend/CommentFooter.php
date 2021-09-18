<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class CommentFooter
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::comment-likes',
            'waterhole::comment-reply',
            'waterhole::comment-replies',
        ];
    }
}
