<?php

namespace Waterhole\Forms;

use Waterhole\Extend;
use Waterhole\Models\User;

class RegistrationForm extends Form
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function fields(): array
    {
        return Extend\RegistrationForm::components(['user' => $this->model]);
    }
}
