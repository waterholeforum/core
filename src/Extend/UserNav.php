<?php

namespace Waterhole\Extend;

use Illuminate\Support\Facades\Auth;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Models\User;
use Waterhole\View\Components\NavHeading;
use Waterhole\View\Components\NavLink;

/**
 * A list of components to render in the user profile navigation menu.
 */
abstract class UserNav
{
    use OrderedList;
}

UserNav::add(
    fn(User $user) => new NavLink(
        label: __('waterhole::user.posts-link'),
        icon: 'tabler-layout-list',
        badge: $user->posts_count,
        href: route('waterhole.user.posts', compact('user')),
        active: request()->routeIs('waterhole.user.posts'),
    ),
    0,
    'posts',
);

UserNav::add(
    fn(User $user) => new NavLink(
        label: __('waterhole::user.comments-link'),
        icon: 'tabler-messages',
        badge: $user->comments_count,
        href: route('waterhole.user.comments', compact('user')),
        active: request()->routeIs('waterhole.user.comments'),
    ),
    0,
    'comments',
);

UserNav::add(
    fn(User $user) => $user->is(Auth::user())
        ? new NavHeading(__('waterhole::user.preferences-heading'))
        : null,
    0,
    'preferences',
);

UserNav::add(
    fn(User $user) => $user->is(Auth::user())
        ? new NavLink(
            label: __('waterhole::user.account-settings-link'),
            icon: 'tabler-fingerprint',
            route: 'waterhole.preferences.account',
        )
        : null,
    0,
    'account',
);

UserNav::add(
    fn(User $user) => $user->is(Auth::user())
        ? new NavLink(
            label: __('waterhole::user.edit-profile-link'),
            icon: 'tabler-user-circle',
            route: 'waterhole.preferences.profile',
        )
        : null,
    0,
    'profile',
);

UserNav::add(
    fn(User $user) => $user->is(Auth::user())
        ? new NavLink(
            label: __('waterhole::user.notification-preferences-link'),
            icon: 'tabler-bell',
            route: 'waterhole.preferences.notifications',
        )
        : null,
    0,
    'notifications',
);
