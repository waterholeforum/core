<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Views\Components\Admin\Version;
use Waterhole\Views\Components\NavLink;

/**
 * A list of components to render in the admin panel navigation menu.
 */
abstract class AdminNav
{
    use OrderedList;
}

AdminNav::add(
    'dashboard',
    new NavLink(
        label: __('waterhole::admin.dashboard-title'),
        icon: 'tabler-report-analytics',
        route: 'waterhole.admin.dashboard',
    ),
    -40,
);

AdminNav::add(
    'structure',
    new NavLink(
        label: __('waterhole::admin.structure-title'),
        icon: 'tabler-layout-list',
        route: 'waterhole.admin.structure',
        active: fn() => request()->routeIs('waterhole.admin.structure*'),
    ),
    -30,
);

AdminNav::add(
    'users',
    new NavLink(
        label: __('waterhole::admin.users-title'),
        icon: 'tabler-user',
        route: 'waterhole.admin.users.index',
        active: fn() => request()->routeIs('waterhole.admin.users*'),
    ),
    -20,
);

AdminNav::add(
    'groups',
    new NavLink(
        label: __('waterhole::admin.groups-title'),
        icon: 'tabler-users',
        route: 'waterhole.admin.groups.index',
        active: fn() => request()->routeIs('waterhole.admin.groups*'),
    ),
    -10,
);

AdminNav::add('version', Version::class, 10);
