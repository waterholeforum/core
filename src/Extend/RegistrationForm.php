<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\UserEmail;
use Waterhole\Forms\Fields\UserName;
use Waterhole\Forms\Fields\UserPassword;

abstract class RegistrationForm
{
    use OrderedList, OfComponents;
}

RegistrationForm::add(UserName::class, position: -30, key: 'name');
RegistrationForm::add(UserEmail::class, position: -20, key: 'email');
RegistrationForm::add(UserPassword::class, position: -10, key: 'password');
