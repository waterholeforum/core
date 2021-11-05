<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\CommentReactButton;
use Waterhole\Views\Components\CommentReplyButton;
use Waterhole\Views\Components\CommentReactions;
use Waterhole\Views\Components\CommentReplies;
use Waterhole\Views\Components\Spacer;

class CommentFooter
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            CommentReactions::class,
            CommentReplies::class,
            Spacer::class,
            CommentReactButton::class,
            CommentReplyButton::class,
        ];
    }
}
