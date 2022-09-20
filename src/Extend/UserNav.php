<?php

namespace Waterhole\Extend;

use Illuminate\Support\Facades\Auth;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Models\User;
use Waterhole\Views\Components\NavHeading;
use Waterhole\Views\Components\NavLink;

/**
 * A list of components to render in the user profile navigation menu.
 */
abstract class UserNav
{
    use OrderedList;
}

UserNav::add(
    'posts',
    fn(User $user) => new NavLink(
        label: __('waterhole::user.posts-link'),
        icon: 'tabler-layout-list',
        badge: $user->posts_count,
        href: route('waterhole.user.posts', compact('user')),
        active: request()->routeIs('waterhole.user.posts'),
    ),
);

UserNav::add(
    'comments',
    fn(User $user) => new NavLink(
        label: __('waterhole::user.comments-link'),
        icon: 'tabler-messages',
        badge: $user->comments_count,
        href: route('waterhole.user.comments', compact('user')),
        active: request()->routeIs('waterhole.user.comments'),
    ),
);

UserNav::add(
    'preferences',
    fn(User $user) => $user->is(Auth::user())
        ? new NavHeading(__('waterhole::user.preferences-heading'))
        : null,
);

UserNav::add(
    'account',
    fn(User $user) => $user->is(Auth::user())
        ? new NavLink(
            label: __('waterhole::user.account-settings-link'),
            icon: 'tabler-fingerprint',
            route: 'waterhole.preferences.account',
        )
        : null,
);

UserNav::add(
    'profile',
    fn(User $user) => $user->is(Auth::user())
        ? new NavLink(
            label: __('waterhole::user.edit-profile-link'),
            icon: 'tabler-user-circle',
            route: 'waterhole.preferences.profile',
        )
        : null,
);

UserNav::add(
    'notifications',
    fn(User $user) => $user->is(Auth::user())
        ? new NavLink(
            label: __('waterhole::user.notification-preferences-link'),
            icon: 'tabler-bell',
            route: 'waterhole.preferences.notifications',
        )
        : null,
);
