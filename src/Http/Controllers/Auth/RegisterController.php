<?php

namespace Waterhole\Http\Controllers\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Waterhole\Auth\Providers;
use Waterhole\Auth\RegistrationPayload;
use Waterhole\Forms\RegistrationForm;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\User;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request, Providers $providers)
    {
        // Copy any URL passed in the `return` query parameter into the session
        // so that after the registration is complete we can redirect back to it.
        if (!redirect()->getIntendedUrl()) {
            redirect()->setIntendedUrl($request->query('return', url()->previous()));
        }

        $form = $this->form(new User());

        if (
            !$form->payload &&
            !config('waterhole.auth.password_enabled', true) &&
            ($provider = $providers->sole())
        ) {
            return redirect()->route('waterhole.sso.login', ['provider' => $provider['name']]);
        }

        return view('waterhole::auth.register', compact('form'));
    }

    public function register(Request $request)
    {
        $form = $this->form($user = new User());

        $form->submit($request);

        if ($form->payload) {
            $user->markEmailAsVerified();

            if ($form->payload->avatar) {
                $user->uploadAvatar(Image::make($form->payload->avatar));
            }

            if ($form->payload->groups) {
                $user->groups()->sync($form->payload->groups);
            }

            $user->authProviders()->create([
                'provider' => $form->payload->provider,
                'identifier' => $form->payload->identifier,
                'last_login_at' => now(),
            ]);
        } elseif (!config('waterhole.auth.password_enabled', true)) {
            abort(400, 'Password registration is disabled');
        }

        event(new Registered($user));

        Auth::login($user);

        // Remove the fragment so that the email verification notice at the top
        // of the page is visible.
        return redirect()
            ->intended(route('waterhole.home'))
            ->withoutFragment();
    }

    private function form(User $user)
    {
        return new RegistrationForm($user, RegistrationPayload::decrypt(request('payload')));
    }
}
