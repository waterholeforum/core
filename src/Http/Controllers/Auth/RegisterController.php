<?php

namespace Waterhole\Http\Controllers\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Waterhole\Forms\UserRegisterForm;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\User;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request)
    {
        // Copy any URL passed in the `return` query parameter into the session
        // so that after the registration is complete we can redirect back to it.
        if (!redirect()->getIntendedUrl()) {
            redirect()->setIntendedUrl($request->query('return', url()->previous()));
        }

        return view('waterhole::auth.register');
    }

    public function register(UserRegisterForm $form)
    {
        $data = $form->safe(['name', 'email', 'password']);

        $data['password'] = Hash::make($data['password']);

        $form->save($user = new User($data));

        event(new Registered($user));

        Auth::login($user);

        // Remove the fragment so that the email verification notice at the top
        // of the page is visible.
        return redirect()
            ->intended(route('waterhole.home'))
            ->withoutFragment();
    }
}
