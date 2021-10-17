<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class LayoutAfter
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            // 'waterhole::components.footer',
        ];
    }
}
