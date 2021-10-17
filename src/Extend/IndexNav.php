<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\IndexStructure;

class IndexNav
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            IndexStructure::class,
        ];
    }
}
