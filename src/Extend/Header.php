<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Views\Components\HeaderAuth;
use Waterhole\Views\Components\HeaderNotifications;
use Waterhole\Views\Components\HeaderSearch;
use Waterhole\Views\Components\HeaderTitle;
use Waterhole\Views\Components\Spacer;

/**
 * A list of components to render in the page header.
 */
abstract class Header
{
    use OrderedList;
}

Header::add('title', HeaderTitle::class);
Header::add('spacer', Spacer::class);
Header::add('search', HeaderSearch::class);
Header::add('notifications', HeaderNotifications::class);
Header::add('auth', HeaderAuth::class);