<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\Admin\Version;
use Waterhole\View\Components\NavLink;

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
    -500,
);

AdminNav::add(
    'structure',
    new NavLink(
        label: __('waterhole::admin.structure-title'),
        icon: 'tabler-layout-list',
        route: 'waterhole.admin.structure',
        active: fn() => request()->routeIs('waterhole.admin.structure*'),
    ),
    -400,
);

AdminNav::add(
    'users',
    new NavLink(
        label: __('waterhole::admin.users-title'),
        icon: 'tabler-user',
        route: 'waterhole.admin.users.index',
        active: fn() => request()->routeIs('waterhole.admin.users*'),
    ),
    -300,
);

AdminNav::add(
    'groups',
    new NavLink(
        label: __('waterhole::admin.groups-title'),
        icon: 'tabler-users',
        route: 'waterhole.admin.groups.index',
        active: fn() => request()->routeIs('waterhole.admin.groups*'),
    ),
    -200,
);

AdminNav::add(
    'reactions',
    new NavLink(
        label: __('waterhole::admin.reactions-title'),
        icon: 'tabler-mood-smile',
        route: 'waterhole.admin.reaction-sets.index',
        active: fn() => request()->routeIs('waterhole.admin.reaction*'),
    ),
    -100,
);

AdminNav::add('version', Version::class, 10);
