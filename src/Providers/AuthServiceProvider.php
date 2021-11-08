<?php

namespace Waterhole\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Waterhole\Policies\PostPolicy;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::before(function ($user, $ability) {
            if ($user->id === 1) {
                return true;
            }
        });
    }
}
