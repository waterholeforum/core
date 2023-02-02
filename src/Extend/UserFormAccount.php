<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\UserEmail;
use Waterhole\Forms\Fields\UserGroups;
use Waterhole\Forms\Fields\UserName;
use Waterhole\Forms\Fields\UserPassword;

abstract class UserFormAccount
{
    use OrderedList, OfComponents;
}

UserFormAccount::add(UserName::class, 0, 'name');
UserFormAccount::add(UserEmail::class, 0, 'email');
UserFormAccount::add(UserPassword::class, 0, 'password');
UserFormAccount::add(UserGroups::class, 0, 'groups');
