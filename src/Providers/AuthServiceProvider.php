<?php

namespace Waterhole\Providers;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Laravel\Socialite\Facades\Socialite;
use Waterhole\Auth\Providers;
use Waterhole\Auth\SsoProvider;
use Waterhole\Models\Permission;
use Waterhole\Models\PermissionCollection;
use Waterhole\Models\User;
use Waterhole\Policies;
use Waterhole\Sso\WaterholeSso;
use Waterhole\Waterhole;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(
            Providers::class,
            fn() => new Providers(config('waterhole.auth.providers')),
        );

        $this->app->singleton(
            'waterhole.permissions',
            fn() => Cache::rememberForever('waterhole.permissions', fn() => Permission::all()),
        );

        $this->app->alias('waterhole.permissions', PermissionCollection::class);

        $this->app->singleton(
            WaterholeSso::class,
            fn() => new WaterholeSso(config('waterhole.auth.sso.secret')),
        );

        Socialite::extend(
            'waterhole_sso',
            fn() => $this->app->make(SsoProvider::class, [
                'url' => config('waterhole.auth.sso.url'),
            ]),
        );

        // Make our policies singletons, to improve performance when we do a lot
        // of auth checks in a single page load.
        $this->app->singleton(Policies\ChannelPolicy::class);
        $this->app->singleton(Policies\CommentPolicy::class);
        $this->app->singleton(Policies\PostPolicy::class);

        Gate::before(function (User $user, $ability, $arguments) {
            // Allow administrators to perform all gated actions.
            if ($user->isAdmin()) {
                return true;
            }

            // Treat users who haven't verified their email like guests.
            if ($user->exists && !$user->hasVerifiedEmail()) {
                return Gate::forUser(null)->allows($ability, ...$arguments) ?:
                    Response::deny(__('waterhole::auth.email-verification-required-message'));
            }

            // Treat suspended users like guests.
            if ($user->isSuspended()) {
                return Gate::forUser(null)->allows($ability, ...$arguments) ?:
                    Response::deny(__('waterhole::user.suspended-message'));
            }
        });

        Gate::after(function (User $user, $ability, $result, $arguments) {
            if ($result === null && strpos($ability, '.') && isset($arguments[0])) {
                return Waterhole::permissions()->can(
                    $user,
                    explode('.', $ability)[1],
                    $arguments[0],
                );
            }
        });

        // We don't want to register policies in the usual way because they are
        // too restrictive - extensions wouldn't be able to add or override
        // abilities. Instead, we define each ability absolutely.

        Gate::define('channel.post', [Policies\ChannelPolicy::class, 'post']);

        Gate::define('comment.create', [Policies\CommentPolicy::class, 'create']);
        Gate::define('comment.edit', [Policies\CommentPolicy::class, 'edit']);
        Gate::define('comment.moderate', [Policies\CommentPolicy::class, 'moderate']);
        Gate::define('comment.react', [Policies\CommentPolicy::class, 'react']);

        Gate::define('post.create', [Policies\PostPolicy::class, 'create']);
        Gate::define('post.edit', [Policies\PostPolicy::class, 'edit']);
        Gate::define('post.delete', [Policies\PostPolicy::class, 'delete']);
        Gate::define('post.move', [Policies\PostPolicy::class, 'move']);
        Gate::define('post.comment', [Policies\PostPolicy::class, 'comment']);
        Gate::define('post.react', [Policies\PostPolicy::class, 'react']);
        Gate::define('post.moderate', [Policies\PostPolicy::class, 'moderate']);
    }
}
