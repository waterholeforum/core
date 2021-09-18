<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class LayoutBefore
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::header',
        ];
    }
}
