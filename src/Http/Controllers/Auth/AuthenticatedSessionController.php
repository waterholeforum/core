<?php

namespace Waterhole\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Http\Requests\Auth\LoginRequest;
use Waterhole\Providers\RouteServiceProvider;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        if (! session()->has('url.intended')) {
            session()->put('url.intended', url()->previous());
        }

        return view('waterhole::auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
