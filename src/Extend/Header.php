<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class Header
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::components.header.title' => 0,
            'waterhole::components.header.auth' => 100,
        ];
    }
}
