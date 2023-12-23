<?php

namespace Waterhole\Http\Controllers\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Waterhole\Auth\Providers;
use Waterhole\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('waterhole.guest');
    }

    public function showLoginForm(Request $request, Providers $providers)
    {
        if (!redirect()->getIntendedUrl()) {
            // Copy any URL passed in the `return` query parameter into the session
            // so that after the login is complete we can redirect back to it.
            redirect()->setIntendedUrl($request->query('return', url()->previous()));
        }

        if (!config('waterhole.auth.password_enabled', true) && ($provider = $providers->sole())) {
            return redirect()->route('waterhole.sso.login', ['provider' => $provider['name']]);
        }

        return view('waterhole::auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $this->ensureIsNotRateLimited($request);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey($request));

            throw ValidationException::withMessages([
                'email' => __('waterhole::auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));

        $request->session()->regenerate();

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('waterhole.home'));
    }

    private function ensureIsNotRateLimited(Request $request)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('waterhole::auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    private function throttleKey(Request $request)
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }
}
