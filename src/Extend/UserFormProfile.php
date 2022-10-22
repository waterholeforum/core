<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\UserBio;
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

UserFormProfile::add('channels', NotificationChannels::class, -60);
UserFormProfile::add('following', Notification::class, -50);
UserFormProfile::add('bio', UserBio::class, -40);
UserFormProfile::add('location', UserLocation::class, -30);
UserFormProfile::add('website', UserWebsite::class, -20);
UserFormProfile::add('showOnline', UserShowOnline::class, -10);
