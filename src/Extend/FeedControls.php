<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\FeedControlsLayout;

class FeedControls
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            FeedControlsLayout::class,
        ];
    }
}
