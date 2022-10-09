<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;

class UserRegisterForm
{
    use OrderedList;
}

UserRegisterForm::add('username', position: -30);
UserRegisterForm::add('email', position: -20);
UserRegisterForm::add('password', position: -10);
UserRegisterForm::add('submit', position: 10);
UserRegisterForm::add('log-in-link', position: 20);
