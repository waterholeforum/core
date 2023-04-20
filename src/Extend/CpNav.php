<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\NavLink;

/**
 * A list of components to render in the Control Panel navigation menu.
 */
abstract class CpNav
{
    use OrderedList;
}

CpNav::add(
    fn() => new NavLink(
        label: __('waterhole::cp.dashboard-title'),
        icon: 'tabler-report-analytics',
        route: 'waterhole.cp.dashboard',
    ),
    -100,
    'dashboard',
);

CpNav::add(
    fn() => new NavLink(
        label: __('waterhole::cp.structure-title'),
        icon: 'tabler-layout-list',
        route: 'waterhole.cp.structure',
        active: request()->routeIs('waterhole.cp.structure*'),
    ),
    -90,
    'structure',
);

CpNav::add(
    fn() => new NavLink(
        label: __('waterhole::cp.taxonomies-title'),
        icon: 'tabler-tags',
        route: 'waterhole.cp.taxonomies.index',
        active: request()->routeIs('waterhole.cp.taxonomies*'),
    ),
    -80,
    'taxonomies',
);

CpNav::add(
    fn() => new NavLink(
        label: __('waterhole::cp.users-title'),
        icon: 'tabler-user',
        route: 'waterhole.cp.users.index',
        active: request()->routeIs('waterhole.cp.users*'),
    ),
    -70,
    'users',
);

CpNav::add(
    fn() => new NavLink(
        label: __('waterhole::cp.groups-title'),
        icon: 'tabler-users',
        route: 'waterhole.cp.groups.index',
        active: request()->routeIs('waterhole.cp.groups*'),
    ),
    -60,
    'groups',
);

CpNav::add(
    fn() => new NavLink(
        label: __('waterhole::cp.reactions-title'),
        icon: 'tabler-mood-smile',
        route: 'waterhole.cp.reaction-sets.index',
        active: request()->routeIs('waterhole.cp.reaction*'),
    ),
    -50,
    'reactions',
);
