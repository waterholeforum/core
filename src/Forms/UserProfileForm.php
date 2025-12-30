<?php

namespace Waterhole\Forms;

use Waterhole\Models\User;

class UserProfileForm extends Form
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function fields(): array
    {
        return resolve(\Waterhole\Extend\Forms\UserForm::class)->profile->components([
            'model' => $this->model,
        ]);
    }
}
