<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\UserEmail;
use Waterhole\Forms\Fields\UserName;
use Waterhole\Forms\Fields\UserPassword;
use Waterhole\View\Components\OAuthButtons;

abstract class RegistrationForm
{
    use OrderedList, OfComponents;
}

RegistrationForm::add('oauth', fn($payload) => $payload ? null : OAuthButtons::class, -40);
RegistrationForm::add('name', UserName::class, position: -30);
RegistrationForm::add('email', UserEmail::class, position: -20);
RegistrationForm::add('password', UserPassword::class, position: -10);
