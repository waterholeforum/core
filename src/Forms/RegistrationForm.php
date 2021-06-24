<?php

namespace Waterhole\Forms;

use Waterhole\Extend;
use Waterhole\Models\User;
use Waterhole\OAuth\Payload;

class RegistrationForm extends Form
{
    public function __construct(User $user, public ?Payload $payload = null)
    {
        parent::__construct($user);
    }

    public function fields(): array
    {
        return Extend\RegistrationForm::components([
            'model' => $this->model,
            'payload' => $this->payload,
        ]);
    }
}
