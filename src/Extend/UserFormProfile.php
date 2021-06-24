<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\UserAvatar;
use Waterhole\Forms\Fields\UserBio;
use Waterhole\Forms\Fields\UserHeadline;
use Waterhole\Forms\Fields\UserLocation;
use Waterhole\Forms\Fields\UserShowOnline;
use Waterhole\Forms\Fields\UserWebsite;

/**
 *
 */
abstract class UserFormProfile
{
    use OrderedList, OfComponents;
}

UserFormProfile::add(UserAvatar::class, -60, 'avatar');
UserFormProfile::add(UserHeadline::class, -50, 'headline');
UserFormProfile::add(UserBio::class, -40, 'bio');
UserFormProfile::add(UserLocation::class, -30, 'location');
UserFormProfile::add(UserWebsite::class, -20, 'website');
UserFormProfile::add(UserShowOnline::class, -10, 'showOnline');
