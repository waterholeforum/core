<?php

namespace Waterhole\View\Components;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\View\Component;

class EmailVerification extends Component
{
    public function shouldRender()
    {
        $user = auth()->user();

        return $user instanceof MustVerifyEmail &&
            !$user->hasVerifiedEmail() &&
            !$user->originalUser();
    }

    public function render()
    {
        return $this->view('waterhole::components.email-verification');
    }
}
