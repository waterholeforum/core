<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Views\Components\NavLink;

class AdminNav
{
    use OrderedList;
}

AdminNav::add('dashboard', new NavLink(
    label: 'Dashboard',
    icon: 'heroicon-o-chart-square-bar',
    route: 'waterhole.admin.dashboard',
));

AdminNav::add('structure', new NavLink(
    label: 'Structure',
    icon: 'heroicon-o-collection',
    route: 'waterhole.admin.structure',
));

AdminNav::add('users', new NavLink(
    label: 'Users',
    icon: 'heroicon-o-user',
    route: 'waterhole.admin.users.index',
    active: fn() => request()->routeIs('waterhole.admin.users*'),
));

AdminNav::add('groups', new NavLink(
    label: 'Groups',
    icon: 'heroicon-o-user-group',
    route: 'waterhole.admin.groups.index',
    active: fn() => request()->routeIs('waterhole.admin.groups*'),
));

AdminNav::add('updates', 'waterhole::admin.nav.updates');

AdminNav::add('version', 'waterhole::admin.nav.version');
