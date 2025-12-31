<?php

namespace Waterhole\Extend\Ui;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\View\Components\NavLink;

/**
 * Navigation links rendered in the control panel sidebar.
 *
 * Use this extender to add, remove, or reorder components rendered in this
 * region of the UI.
 */
class CpNav extends ComponentList
{
    public function __construct()
    {
        $this->add(
            fn() => new NavLink(
                label: __('waterhole::cp.dashboard-title'),
                icon: 'tabler-report-analytics',
                route: 'waterhole.cp.dashboard',
            ),
            'dashboard',
        );

        $this->add(
            fn() => new NavLink(
                label: __('waterhole::cp.structure-title'),
                icon: 'tabler-layout-list',
                route: 'waterhole.cp.structure',
                active: request()->routeIs('waterhole.cp.structure*'),
            ),
            'structure',
        );

        $this->add(
            fn() => new NavLink(
                label: __('waterhole::cp.taxonomies-title'),
                icon: 'tabler-tags',
                route: 'waterhole.cp.taxonomies.index',
                active: request()->routeIs('waterhole.cp.taxonomies*'),
            ),
            'taxonomies',
        );

        $this->add(
            fn() => new NavLink(
                label: __('waterhole::cp.users-title'),
                icon: 'tabler-user',
                route: 'waterhole.cp.users.index',
                active: request()->routeIs('waterhole.cp.users*'),
            ),
            'users',
        );

        $this->add(
            fn() => new NavLink(
                label: __('waterhole::cp.groups-title'),
                icon: 'tabler-users',
                route: 'waterhole.cp.groups.index',
                active: request()->routeIs('waterhole.cp.groups*'),
            ),
            'groups',
        );

        $this->add(
            fn() => new NavLink(
                label: __('waterhole::cp.reactions-title'),
                icon: 'tabler-mood-smile',
                route: 'waterhole.cp.reaction-sets.index',
                active: request()->routeIs('waterhole.cp.reaction*'),
            ),
            'reactions',
        );
    }
}
