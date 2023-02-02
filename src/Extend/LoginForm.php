<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\OAuthButtons;

class LoginForm
{
    use OrderedList, OfComponents;
}

LoginForm::add(OAuthButtons::class, -30, 'oauth');
LoginForm::add(null, -20, 'email');
LoginForm::add(null, -10, 'password');
LoginForm::add(null, 10, 'submit');
LoginForm::add(null, 20, 'sign-up-link');
