<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class PostFooter
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::components.post.footer.likes',
            'waterhole::components.post.footer.replies',
            'waterhole::components.post.footer.actions',
        ];
    }
}
