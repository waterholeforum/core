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

UserInfo::add('groups', UserGroups::class);
UserInfo::add('location', UserLocation::class);
UserInfo::add('website', UserWebsite::class);
UserInfo::add('joined', UserJoined::class);
UserInfo::add('lastSeen', UserLastSeen::class);
