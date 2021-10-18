<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\FeedControlsLayout;
use Waterhole\Views\Components\FeedControlsMarkRead;

class FeedControls
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            FeedControlsMarkRead::class,
            FeedControlsLayout::class,
        ];
    }
}
