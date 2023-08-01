<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
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
    use OrderedList, OfComponents;
}

Header::add(HeaderTitle::class, -100, 'title');
Header::add(HeaderBreadcrumb::class, -90, 'breadcrumb');

Header::add(Spacer::class, 0, 'spacer');

Header::add(HeaderSearch::class, 70, 'search');
Header::add(ThemeSelector::class, 80, 'theme');
Header::add(HeaderNotifications::class, 90, 'notifications');

Header::add(HeaderGuest::class, 100, 'guest');
Header::add(HeaderUser::class, 100, 'user');
