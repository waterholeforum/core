<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\AdminPages;

class AdminNav
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            AdminPages::class,
        ];
    }
}
