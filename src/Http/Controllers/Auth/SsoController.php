<?php

namespace Waterhole\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Waterhole\Auth\Providers;
use Waterhole\Auth\SsoPayload;
use Waterhole\Models\AuthProvider;
use Waterhole\Models\User;
use Waterhole\Sso\PendingUser;

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

        $payload = new SsoPayload(
            $provider,
            new PendingUser(
                identifier: $externalUser->getId(),
                email: $externalUser->getEmail(),
                name: $externalUser->getNickname() ?: $externalUser->getName(),
                avatar: $externalUser->getAvatar(),
                groups: method_exists($externalUser, 'getGroups')
                    ? $externalUser->getGroups()
                    : null,
            ),
        );

        $record = [
            'provider' => $payload->provider,
            'identifier' => $payload->user->identifier,
        ];

        if ($provider = AuthProvider::firstWhere($record)) {
            $provider->touch('last_login_at');
            $user = $provider->user;
        } elseif ($user = User::firstWhere('email', $payload->user->email)) {
            $user->authProviders()->create($record + ['last_login_at' => now()]);
        }

        if (isset($user)) {
            Auth::login($user, true);

            return redirect()->intended(route('waterhole.home'));
        }

        if (!config('waterhole.auth.allow_registration', true)) {
            session()->flash('danger', __('waterhole::auth.failed'));

            return redirect()->route('waterhole.login');
        }

        if (!redirect()->getIntendedUrl()) {
            redirect()->setIntendedUrl($request->query('return', url()->previous()));
        }

        return redirect()->route('waterhole.register.payload', compact('payload'));
    }
}
