<?php

namespace Waterhole\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $name = $externalUser->getNickname() ?: $externalUser->getName();
        $avatar = $externalUser->getAvatar();

        if ($record = AuthProvider::firstWhere(compact('provider', 'identifier'))) {
            $record->touch('last_login_at');

            Auth::login($record->user);

            return redirect()->intended(route('waterhole.home'));
        }

        if (User::firstWhere(compact('email'))) {
            session()->flash('danger', 'There is already an account with this email address.');

            return redirect()->route('waterhole.login');
        }

        if (!redirect()->getIntendedUrl()) {
            redirect()->setIntendedUrl($request->query('return', url()->previous()));
        }

        $payload = new Payload(
            provider: $provider,
            identifier: $identifier,
            email: $email,
            name: $name,
            avatar: $avatar,
        );

        return redirect()->route('waterhole.register', ['oauth' => $payload->encrypt()]);
    }
}
