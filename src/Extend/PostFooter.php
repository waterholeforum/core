<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\PostLikes;
use Waterhole\Views\Components\PostReplies;

class PostFooter
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            PostLikes::class,
            PostReplies::class,
        ];
    }
}
