<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\PostReactButton;
use Waterhole\Views\Components\PostReactions;
use Waterhole\Views\Components\PostReplies;

class PostFooter
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            PostReactions::class,
            PostReplies::class,
            PostReactButton::class,
        ];
    }
}
