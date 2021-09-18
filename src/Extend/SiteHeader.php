<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class SiteHeader
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::header-title',
            'waterhole::spacer',
            'waterhole::header-auth',
        ];
    }
}
