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
    new NavLink(
        label: __('waterhole::admin.dashboard-title'),
        icon: 'tabler-report-analytics',
        route: 'waterhole.admin.dashboard',
    ),
    -100,
    'dashboard',
);

AdminNav::add(
    new NavLink(
        label: __('waterhole::admin.structure-title'),
        icon: 'tabler-layout-list',
        route: 'waterhole.admin.structure',
        active: fn() => request()->routeIs('waterhole.admin.structure*'),
    ),
    -90,
    'structure',
);

AdminNav::add(
    new NavLink(
        label: __('waterhole::admin.taxonomies-title'),
        icon: 'tabler-tags',
        route: 'waterhole.admin.taxonomies.index',
        active: fn() => request()->routeIs('waterhole.admin.taxonomies*'),
    ),
    -80,
    'taxonomies',
);

AdminNav::add(
    new NavLink(
        label: __('waterhole::admin.users-title'),
        icon: 'tabler-user',
        route: 'waterhole.admin.users.index',
        active: fn() => request()->routeIs('waterhole.admin.users*'),
    ),
    -70,
    'users',
);

AdminNav::add(
    new NavLink(
        label: __('waterhole::admin.groups-title'),
        icon: 'tabler-users',
        route: 'waterhole.admin.groups.index',
        active: fn() => request()->routeIs('waterhole.admin.groups*'),
    ),
    -60,
    'groups',
);

AdminNav::add(
    new NavLink(
        label: __('waterhole::admin.reactions-title'),
        icon: 'tabler-mood-smile',
        route: 'waterhole.admin.reaction-sets.index',
        active: fn() => request()->routeIs('waterhole.admin.reaction*'),
    ),
    -50,
    'reactions',
);

AdminNav::add(Version::class, 10, 'version');
