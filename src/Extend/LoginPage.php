<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\OAuthButtons;

class LoginPage
{
    use OrderedList, OfComponents;
}

LoginPage::add(OAuthButtons::class, -30, 'oauth');

if (config('waterhole.auth.password_enabled')) {
    LoginPage::add(null, -20, 'email');
    LoginPage::add(null, -10, 'password');
    LoginPage::add(null, 10, 'submit');
    LoginPage::add(null, 20, 'sign-up-link');
}
