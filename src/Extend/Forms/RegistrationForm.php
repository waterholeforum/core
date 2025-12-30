<?php

namespace Waterhole\Extend\Forms;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\Forms\Fields\UserEmail;
use Waterhole\Forms\Fields\UserName;
use Waterhole\Forms\Fields\UserPassword;

/**
 * List of fields for the registration form.
 *
 * Use this extender to add, remove, or reorder fields on the public
 * registration form.
 */
class RegistrationForm extends ComponentList
{
    public function __construct()
    {
        $this->add('name', UserName::class);
        $this->add('email', UserEmail::class);
        $this->add('password', UserPassword::class);
    }
}
