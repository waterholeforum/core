<?php

namespace Waterhole\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Waterhole\Http\Controllers\Controller;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        return view('waterhole::auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink($request->only('email'));

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __("waterhole::$status"))
            : back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __("waterhole::$status")]);
    }
}
