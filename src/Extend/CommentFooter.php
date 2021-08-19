<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class PostFooter
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::components.comment.footer.likes',
            'waterhole::components.comment.footer.replies',
            'waterhole::components.comment.footer.actions',
        ];
    }
}
