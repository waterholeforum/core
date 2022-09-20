<?php

namespace Waterhole\Extend;

use Auth;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Views\Components\MenuDivider;
use Waterhole\Views\Components\MenuItem;

/**
 * A list of components to render in the user menu.
 */
abstract class UserMenu
{
    use OrderedList;
}

UserMenu::add(
    'profile',
    new MenuItem(
        icon: 'tabler-user',
        label: __('waterhole::user.profile-link'),
        href: Auth::user()->url,
    ),
);

UserMenu::add(
    'preferences',
    new MenuItem(
        icon: 'tabler-settings',
        label: __('waterhole::user.preferences-link'),
        href: route('waterhole.preferences'),
    ),
);

UserMenu::add('divider', MenuDivider::class);

if (Auth::user()->can('administrate')) {
    UserMenu::add(
        'administration',
        new MenuItem(
            icon: 'tabler-tool',
            label: __('waterhole::user.administration-link'),
            href: route('waterhole.admin.dashboard'),
        ),
    );
}
