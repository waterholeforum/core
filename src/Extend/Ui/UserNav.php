<?php

namespace Waterhole\Extend\Ui;

use Illuminate\Support\Facades\Auth;
use Waterhole\Extend\Support\ComponentList;
use Waterhole\Models\User;
use Waterhole\View\Components\NavHeading;
use Waterhole\View\Components\NavLink;

/**
 * Links rendered in the user profile navigation.
 *
 * Use this extender to add, remove, or reorder components rendered in this
 * region of the UI.
 */
class UserNav extends ComponentList
{
    public function __construct()
    {
        $this->add(
            fn(User $user) => new NavLink(
                label: __('waterhole::user.posts-link'),
                icon: 'tabler-layout-list',
                badge: $user->posts_count,
                href: route('waterhole.user.posts', compact('user')),
                active: request()->routeIs('waterhole.user.posts'),
            ),
            'posts',
        );

        $this->add(
            fn(User $user) => new NavLink(
                label: __('waterhole::user.comments-link'),
                icon: 'tabler-messages',
                badge: $user->comments_count,
                href: route('waterhole.user.comments', compact('user')),
                active: request()->routeIs('waterhole.user.comments'),
            ),
            'comments',
        );

        $this->add(
            fn(User $user) => $user->is(Auth::user())
                ? new NavHeading(__('waterhole::user.preferences-heading'))
                : null,
            'preferences',
        );

        $this->add(
            fn(User $user) => $user->is(Auth::user())
                ? new NavLink(
                    label: __('waterhole::user.account-settings-link'),
                    icon: 'tabler-fingerprint',
                    route: 'waterhole.preferences.account',
                )
                : null,
            'account',
        );

        $this->add(
            fn(User $user) => $user->is(Auth::user())
                ? new NavLink(
                    label: __('waterhole::user.edit-profile-link'),
                    icon: 'tabler-user-circle',
                    route: 'waterhole.preferences.profile',
                )
                : null,
            'profile',
        );

        $this->add(
            fn(User $user) => $user->is(Auth::user())
                ? new NavLink(
                    label: __('waterhole::user.notification-preferences-link'),
                    icon: 'tabler-bell',
                    route: 'waterhole.preferences.notifications',
                )
                : null,
            'notifications',
        );
    }
}
