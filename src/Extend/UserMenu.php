<?php

namespace Waterhole\Extend;

use Auth;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\MenuDivider;
use Waterhole\View\Components\MenuItem;

/**
 * A list of components to render in the user menu.
 */
abstract class UserMenu
{
    use OrderedList;
}

UserMenu::add(
    fn() => new MenuItem(
        icon: 'tabler-user',
        label: __('waterhole::user.profile-link'),
        href: Auth::user()->url,
    ),
    0,
    'profile',
);

UserMenu::add(
    fn() => new MenuItem(
        icon: 'tabler-settings',
        label: __('waterhole::user.preferences-link'),
        href: route('waterhole.preferences'),
    ),
    0,
    'preferences',
);

UserMenu::add(MenuDivider::class, 0, 'divider');

UserMenu::add(
    fn() => Auth::user()->can('administrate')
        ? (new MenuItem(
            icon: 'tabler-tool',
            label: __('waterhole::user.administration-link'),
            href: route('waterhole.admin.dashboard'),
        ))->withAttributes(['data-turbo' => 'false'])
        : null,
    0,
    'administration',
);
