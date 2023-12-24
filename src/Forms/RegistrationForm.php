<?php

namespace Waterhole\Forms;

use Waterhole\Auth\SsoPayload;
use Waterhole\Extend;
use Waterhole\Models\User;

class RegistrationForm extends Form
{
    public function __construct(User $user, public ?SsoPayload $payload = null)
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
