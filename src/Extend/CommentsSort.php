<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Sorts\Oldest;
use Waterhole\Sorts\Top;

class CommentsSort
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            Oldest::class,
            Top::class,
        ];
    }
}
