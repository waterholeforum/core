<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Sorts\Alphabetical;
use Waterhole\Sorts\Latest;
use Waterhole\Sorts\NewActivity;
use Waterhole\Sorts\Oldest;
use Waterhole\Sorts\Top;

class FeedSort
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            Latest::class,
            NewActivity::class,
            Oldest::class,
            Top::class,
            Alphabetical::class,
        ];
    }
}
