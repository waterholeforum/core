<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class SiteHeader
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::components.header.title',
            'waterhole::components.spacer',
            'waterhole::components.header.auth',
        ];
    }
}
