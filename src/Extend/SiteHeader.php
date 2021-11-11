<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesComponents;
use Waterhole\Views\Components\HeaderAuth;
use Waterhole\Views\Components\HeaderNotifications;
use Waterhole\Views\Components\HeaderSearch;
use Waterhole\Views\Components\HeaderTitle;
use Waterhole\Views\Components\Spacer;

class SiteHeader
{
    use ManagesComponents;

    protected static function defaultComponents(): array
    {
        return [
            HeaderTitle::class,
            Spacer::class,
            HeaderSearch::class,
            HeaderNotifications::class,
            HeaderAuth::class,
        ];
    }
}
