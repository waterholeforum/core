<?php

namespace Waterhole\Http\Controllers\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Waterhole\Auth\Providers;
use Waterhole\Auth\SsoPayload;
use Waterhole\Forms\RegistrationForm;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Group;
use Waterhole\Models\User;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('waterhole.guest');
    }

    public function showRegistrationForm(Request $request, Providers $providers)
    {
        // Copy any URL passed in the `return` query parameter into the session
        // so that after the registration is complete we can redirect back to it.
        if (!redirect()->getIntendedUrl()) {
            redirect()->setIntendedUrl($request->query('return', url()->previous()));
        }

        $form = $this->form(new User());

        if (!config('waterhole.auth.password_enabled', true) && ($provider = $providers->sole())) {
            return redirect()->route('waterhole.sso.login', compact('provider'));
        }

        return view('waterhole::auth.register', compact('form'));
    }

    public function registerWithPayload(string $payload)
    {
        $form = $this->form(new User(), $payload);

        return view('waterhole::auth.register', compact('form'));
    }

    public function register(Request $request)
    {
        $form = $this->form($user = new User(), $request->input('payload'));

        $form->submit($request);

        if ($form->payload) {
            $user->markEmailAsVerified();

            if ($form->payload->user->avatar) {
                $user->uploadAvatar(Image::make($form->payload->user->avatar));
            }

            if ($form->payload->user->groups) {
                $user->groups()->sync($form->payload->user->groups);
            }

            $user->authProviders()->create([
                'provider' => $form->payload->provider,
                'identifier' => $form->payload->user->identifier,
                'last_login_at' => now(),
            ]);
        } elseif (!config('waterhole.auth.password_enabled', true)) {
            abort(400, 'Password registration is disabled');
        }

        $user
            ->groups()
            ->syncWithoutDetaching(Group::query()->where('auto_assign', true)->pluck('id'));

        event(new Registered($user));

        if (!$request->attributes->has('waterhole_original_user')) {
            Auth::login($user);
        }

        // Remove the fragment so that the email verification notice at the top
        // of the page is visible.
        return redirect()->intended(route('waterhole.home'))->withoutFragment();
    }

    private function form(User $user, ?string $payload = null)
    {
        return new RegistrationForm($user, $payload ? SsoPayload::decrypt($payload) : null);
    }
}
