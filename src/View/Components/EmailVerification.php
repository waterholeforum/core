<?php

namespace Waterhole\View\Components;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\View\Component;

class EmailVerification extends Component
{
    public function shouldRender()
    {
        $user = auth()->user();

        return $user instanceof MustVerifyEmail && !$user->hasVerifiedEmail();
    }

    public function render()
    {
        return view('waterhole::components.email-verification');
    }
}
