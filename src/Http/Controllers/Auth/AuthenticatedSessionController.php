<?php

namespace Waterhole\Http\Controllers\Auth;

use Illuminate\Support\Facades\Redirect;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Http\Requests\Auth\LoginRequest;
use Waterhole\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (! session()->has('url.intended')) {
            session()->put('url.intended', url()->previous());
        }

        return view('waterhole::auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \Waterhole\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
