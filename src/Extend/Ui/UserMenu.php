<?php

namespace Waterhole\Extend\Ui;

use Illuminate\Support\Facades\Auth;
use Waterhole\Extend\Support\ComponentList;
use Waterhole\View\Components\MenuDivider;
use Waterhole\View\Components\MenuItem;

/**
 * Items rendered in the user menu dropdown.
 *
 * Use this extender to add, remove, or reorder components rendered in this
 * region of the UI.
 */
class UserMenu extends ComponentList
{
    public function __construct()
    {
        $this->add(
            fn() => new MenuItem(
                icon: 'tabler-user',
                label: __('waterhole::user.profile-link'),
                href: Auth::user()->url,
            ),
            'profile',
        );

        $this->add(
            fn() => new MenuItem(
                icon: 'tabler-settings',
                label: __('waterhole::user.preferences-link'),
                href: route('waterhole.preferences'),
            ),
            'preferences',
        );

        $this->add(MenuDivider::class, 'divider');

        $this->add(
            fn() => Auth::user()->can('waterhole.administrate')
                ? (new MenuItem(
                    icon: 'tabler-tool',
                    label: __('waterhole::user.administration-link'),
                    href: route('waterhole.cp.dashboard'),
                ))->withAttributes(['data-turbo' => 'false'])
                : null,
            'administration',
        );
    }
}
