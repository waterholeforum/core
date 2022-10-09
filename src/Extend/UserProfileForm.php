<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;

/**
 *
 */
abstract class UserProfileForm
{
    use OrderedList;
}

UserProfileForm::add('avatar', null, -60);
UserProfileForm::add('headline', null, -50);
UserProfileForm::add('bio', null, -40);
UserProfileForm::add('location', null, -30);
UserProfileForm::add('website', null, -20);
UserProfileForm::add('privacy', null, -10);
