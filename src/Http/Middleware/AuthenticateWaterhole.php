<?php

namespace Waterhole\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Waterhole\Auth\AuthenticatesWaterhole;
use Waterhole\Models\User;

class AuthenticateWaterhole
{
    public function handle(Request $request, Closure $next)
    {
        $originalUser = Auth::user();

        if (
            $originalUser instanceof AuthenticatesWaterhole &&
            ($payload = $originalUser->toWaterholePayload())
        ) {
            $record = [
                'provider' => $payload->provider,
                'identifier' => $payload->user->identifier,
            ];

            $user = User::whereRelation('authProviders', $record)->first();

            if (!$user && ($user = User::firstWhere('email', $payload->user->email))) {
                $user->authProviders()->create($record);
            }

            if (!$user && !$request->routeIs('waterhole.register*')) {
                return redirect()->route('waterhole.register.payload', compact('payload'));
            }

            $guard = Auth::guard(config('waterhole.auth.guard', 'web'));

            if ($user) {
                $user->setOriginalUser($originalUser);

                $guard->setUser($user);
            } else {
                // This is a macro defined in AuthServiceProvider, pending
                // https://github.com/laravel/framework/pull/49506
                $guard->logoutOnce();
            }
        }

        return $next($request);
    }
}
