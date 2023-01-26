<?php

namespace Waterhole\Providers;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Waterhole\Models\Permission;
use Waterhole\Models\PermissionCollection;
use Waterhole\Models\User;
use Waterhole\OAuth\Providers;
use Waterhole\Policies;
use Waterhole\Waterhole;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(
            Providers::class,
            fn() => new Providers(config('waterhole.auth.oauth_providers')),
        );

        $this->app->singleton('waterhole.permissions', fn() => Permission::all());
        $this->app->alias('waterhole.permissions', PermissionCollection::class);

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
        });

        Gate::after(function (User $user, $ability, $result, $arguments) {
            if ($result === null && strpos($ability, '.')) {
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
        Gate::define('comment.delete', [Policies\CommentPolicy::class, 'delete']);
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
