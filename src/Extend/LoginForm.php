<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\OAuthButtons;

class LoginForm
{
    use OrderedList, OfComponents;
}

LoginForm::add('oauth', OAuthButtons::class, -30);
LoginForm::add('email', null, -20);
LoginForm::add('password', null, -10);
LoginForm::add('submit', null, 10);
LoginForm::add('sign-up-link', null, 20);
