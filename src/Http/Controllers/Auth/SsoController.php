<?php

namespace Waterhole\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Waterhole\Auth\Providers;
use Waterhole\Auth\RegistrationPayload;
use Waterhole\Models\AuthProvider;
use Waterhole\Models\User;

class SsoController
{
    public function login(Providers $providers, string $provider)
    {
        abort_unless($providers->has($provider), 400);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(Providers $providers, Request $request, string $provider)
    {
        abort_unless($providers->has($provider), 400);

        $externalUser = Socialite::driver($provider)->user();
        $identifier = $externalUser->getId();
        $email = $externalUser->getEmail();

        if ($record = AuthProvider::firstWhere(compact('provider', 'identifier'))) {
            $record->touch('last_login_at');

            Auth::login($record->user, true);

            return redirect()->intended(route('waterhole.home'));
        }

        if ($user = User::firstWhere('email', $email)) {
            // TODO: ask the user to enter their password (if they have one)
            // before associating this provider with their account

            $user->authProviders()->create([
                'provider' => $provider,
                'identifier' => $identifier,
                'last_login_at' => now(),
            ]);

            Auth::login($user, true);

            return redirect()->intended(route('waterhole.home'));
        }

        if (!Route::has('waterhole.register')) {
            session()->flash('danger', __('waterhole::auth.failed'));

            return redirect()->route('waterhole.login');
        }

        if (!redirect()->getIntendedUrl()) {
            redirect()->setIntendedUrl($request->query('return', url()->previous()));
        }

        $payload = new RegistrationPayload(
            provider: $provider,
            identifier: $identifier,
            email: $email,
            name: $externalUser->getNickname() ?: $externalUser->getName(),
            avatar: $externalUser->getAvatar(),
            groups: method_exists($externalUser, 'getGroups') ? $externalUser->getGroups() : null,
        );

        return redirect()->route('waterhole.register', ['payload' => $payload->encrypt()]);
    }
}
