<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;

class LoginForm
{
    use OrderedList;
}

LoginForm::add('email', -20);
LoginForm::add('password', -10);
LoginForm::add('submit', 10);
LoginForm::add('sign-up-link', 20);
