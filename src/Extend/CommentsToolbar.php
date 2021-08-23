<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class CommentsToolbar
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::post.comments.title',
            'waterhole::post.comments.sort',
            'waterhole::ui.spacer',
            'waterhole::post.comments.pagination',
            'waterhole::post.comments.reply-button',
        ];
    }
}
