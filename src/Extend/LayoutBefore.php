<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\Header;

class LayoutBefore
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            Header::class,
        ];
    }
}
