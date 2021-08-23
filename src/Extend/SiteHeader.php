<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class SiteHeader
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::header.title',
            'waterhole::ui.spacer',
            'waterhole::header.auth',
        ];
    }
}
