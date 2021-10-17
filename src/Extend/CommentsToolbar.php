<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class CommentsToolbar
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::components.post-comments-title',
            'waterhole::components.post-comments-sort',
            'waterhole::components.spacer',
            'waterhole::components.post-comments-pagination',
            'waterhole::components.post-comments-reply-button',
        ];
    }
}
