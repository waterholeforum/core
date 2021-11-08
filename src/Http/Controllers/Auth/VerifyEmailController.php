<?php

namespace Waterhole\Http\Controllers\Auth;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Providers\RouteServiceProvider;

class VerifyEmailController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:6,1']);
        $this->middleware('signed')->only('verify');
    }

    public function verify(Request $request)
    {
        if (! hash_equals((string) $request->route('id'), (string) $request->user()->getKey())) {
            throw new AuthorizationException();
        }

        $user = $request->user();
        $user->email = $request->query('email');
        $user->markEmailAsVerified();

        event(new Verified($request->user()));

        return redirect()->intended(RouteServiceProvider::HOME)
            ->with('success', 'Thanks for verifying your email!');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Email verification sent.');
    }
}
