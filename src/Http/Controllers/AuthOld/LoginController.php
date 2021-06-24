<?php

namespace Waterhole\Http\Controllers\AuthOld;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\View\View;
use Waterhole\Http\Controllers\Controller;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected string $redirectTo = '/';

    public function showLoginForm(): View
    {
        return view('waterhole::auth.login');
    }
}
