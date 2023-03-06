<?php

namespace Waterhole\Http\Controllers\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Waterhole\Http\Controllers\Controller;

class ResetPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request)
    {
        return view('waterhole::auth.reset-password', ['request' => $request]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation') + [
                'token' => $request->route('token'),
            ],
            function ($user) use ($request) {
                $user
                    ->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])
                    ->save();

                event(new PasswordReset($user));
            },
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
            ? redirect()
                ->route('waterhole.login')
                ->with('success', __("waterhole::$status"))
            : back()
                ->withInput($request->only('email'))
                ->withErrors(['token' => __("waterhole::$status")]);
    }
}
