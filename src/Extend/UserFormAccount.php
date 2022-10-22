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

UserFormAccount::add('name', UserName::class);
UserFormAccount::add('email', UserEmail::class);
UserFormAccount::add('password', UserPassword::class);
UserFormAccount::add('groups', UserGroups::class);
