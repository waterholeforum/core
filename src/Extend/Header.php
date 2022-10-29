<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\HeaderBreadcrumb;
use Waterhole\View\Components\HeaderGuest;
use Waterhole\View\Components\HeaderNotifications;
use Waterhole\View\Components\HeaderSearch;
use Waterhole\View\Components\HeaderTitle;
use Waterhole\View\Components\HeaderUser;
use Waterhole\View\Components\Spacer;
use Waterhole\View\Components\ThemeSelector;

/**
 * A list of components to render in the page header.
 */
abstract class Header
{
    use OrderedList;
}

Header::add('title', HeaderTitle::class);
Header::add('breadcrumb', HeaderBreadcrumb::class);
Header::add('spacer', Spacer::class);

Header::add('search', HeaderSearch::class);

Header::add('notifications', HeaderNotifications::class);
Header::add('guest', HeaderGuest::class);
Header::add('user', HeaderUser::class);

Header::add('theme', ThemeSelector::class);
