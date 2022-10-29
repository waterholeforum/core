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

UserFormProfile::add('avatar', UserAvatar::class, -60);
UserFormProfile::add('headline', UserHeadline::class, -50);
UserFormProfile::add('bio', UserBio::class, -40);
UserFormProfile::add('location', UserLocation::class, -30);
UserFormProfile::add('website', UserWebsite::class, -20);
UserFormProfile::add('showOnline', UserShowOnline::class, -10);
