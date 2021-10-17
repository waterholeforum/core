<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\CommentButtonReact;
use Waterhole\Views\Components\CommentReactions;
use Waterhole\Views\Components\CommentReplies;

class CommentMeta
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            CommentReactions::class,
            CommentReplies::class,
            CommentButtonReact::class,
        ];
    }
}
