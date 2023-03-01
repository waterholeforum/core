<?php

namespace Waterhole\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Waterhole\Models\AuthProvider;
use Waterhole\Models\User;
use Waterhole\OAuth\Payload;
use Waterhole\OAuth\Providers;

class OAuthController
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

            Auth::login($record->user);

            return redirect()->intended(route('waterhole.home'));
        }

        if ($user = User::firstWhere(compact('email'))) {
            // TODO: ask the user to enter their password (if they have one)
            // before associating this provider with their account

            $user->authProviders()->create([
                'provider' => $provider,
                'identifier' => $identifier,
                'last_login_at' => now(),
            ]);

            Auth::login($user);

            return redirect()->intended(route('waterhole.home'));
        }

        if (!Route::has('waterhole.register')) {
            session()->flash(
                'danger',
                'No account exists with this email address, and registration is disabled.',
            );

            return redirect()->route('waterhole.login');
        }

        if (!redirect()->getIntendedUrl()) {
            redirect()->setIntendedUrl($request->query('return', url()->previous()));
        }

        $payload = new Payload(
            provider: $provider,
            identifier: $identifier,
            email: $email,
            name: $externalUser->getNickname() ?: $externalUser->getName(),
            avatar: $externalUser->getAvatar(),
        );

        return redirect()->route('waterhole.register', ['oauth' => $payload->encrypt()]);
    }
}
