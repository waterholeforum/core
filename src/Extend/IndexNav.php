<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;

class IndexNav
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            'waterhole::components.index.structure',
        ];
    }
}
