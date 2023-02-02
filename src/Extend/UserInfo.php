<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\UserGroups;
use Waterhole\View\Components\UserJoined;
use Waterhole\View\Components\UserLastSeen;
use Waterhole\View\Components\UserLocation;
use Waterhole\View\Components\UserWebsite;

/**
 * A list of components to render in the user profile.
 */
abstract class UserInfo
{
    use OrderedList;
}

UserInfo::add(UserGroups::class, 0, 'groups');
UserInfo::add(UserLocation::class, 0, 'location');
UserInfo::add(UserWebsite::class, 0, 'website');
UserInfo::add(UserJoined::class, 0, 'joined');
UserInfo::add(UserLastSeen::class, 0, 'lastSeen');
