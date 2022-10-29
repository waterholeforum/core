<?php

namespace Waterhole\Forms;

use Waterhole\Extend;
use Waterhole\Models\User;

class UserProfileForm extends Form
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function fields(): array
    {
        return Extend\UserFormProfile::components(['model' => $this->model]);
    }
}
