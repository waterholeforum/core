<?php

namespace Waterhole\Forms;

use Waterhole\Auth\RegistrationPayload;
use Waterhole\Extend;
use Waterhole\Models\User;

class RegistrationForm extends Form
{
    public function __construct(User $user, public ?RegistrationPayload $payload = null)
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
