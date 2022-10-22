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

RegistrationForm::add('name', UserName::class, position: -30);
RegistrationForm::add('email', UserEmail::class, position: -20);
RegistrationForm::add('password', UserPassword::class, position: -10);
