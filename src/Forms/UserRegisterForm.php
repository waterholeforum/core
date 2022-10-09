<?php

namespace Waterhole\Forms;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Waterhole\Extend;

class UserRegisterForm extends Form
{
    public static function fields(): array
    {
        return Extend\UserRegisterForm::build();
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('users')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password' => ['required', Password::defaults()],
        ];
    }
}
