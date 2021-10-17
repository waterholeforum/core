<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\CommentButtonReact;
use Waterhole\Views\Components\CommentButtonReply;

class CommentButtons
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            // CommentButtonReact::class,
            CommentButtonReply::class,
        ];
    }
}
