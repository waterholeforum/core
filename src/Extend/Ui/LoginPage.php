<?php

namespace Waterhole\Extend\Ui;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\View\Components\AuthButtons;

/**
 * Components rendered on the login page.
 *
 * Use this extender to add, remove, or reorder components rendered in this
 * region of the UI.
 */
class LoginPage extends ComponentList
{
    public function __construct()
    {
        $this->add('auth-buttons', AuthButtons::class);

        if (config('waterhole.auth.password_enabled', true)) {
            $this->add('email');
            $this->add('password');
            $this->add('submit');
            $this->add('sign-up-link');
        }
    }
}
