<?php

namespace Waterhole\Providers;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Waterhole\Models\Group;
use Waterhole\Models\Permission;
use Waterhole\Models\PermissionCollection;
use Waterhole\Models\User;
use Waterhole\Policies;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton('waterhole.permissions', fn() => Permission::all());
        $this->app->alias('waterhole.permissions', PermissionCollection::class);

        Gate::before(function (User $user, $ability, $arguments) {
            // Allow administrators to perform all gated actions.
            if ($user->groups->contains(Group::ADMIN_ID)) {
                return true;
            }

            // Treat users who haven't verified their email like guests.
            if ($user->exists && !$user->hasVerifiedEmail()) {
                return Gate::forUser(null)->allows($ability, ...$arguments) ?:
                    // TODO: translate
                    Response::deny('You must verify your email address.');
            }
        });

        // We don't want to register policies in the usual way because they are
        // too restrictive - extensions wouldn't be able to add or override
        // abilities. Instead, we define each ability absolutely.

        Gate::define('channel.post', [Policies\ChannelPolicy::class, 'post']);

        Gate::define('comment.create', [Policies\CommentPolicy::class, 'create']);
        Gate::define('comment.edit', [Policies\CommentPolicy::class, 'edit']);
        Gate::define('comment.delete', [Policies\CommentPolicy::class, 'delete']);
        Gate::define('comment.like', [Policies\CommentPolicy::class, 'like']);

        Gate::define('post.create', [Policies\PostPolicy::class, 'create']);
        Gate::define('post.edit', [Policies\PostPolicy::class, 'edit']);
        Gate::define('post.delete', [Policies\PostPolicy::class, 'delete']);
        Gate::define('post.move', [Policies\PostPolicy::class, 'move']);
        Gate::define('post.comment', [Policies\PostPolicy::class, 'comment']);
        Gate::define('post.like', [Policies\PostPolicy::class, 'like']);
        Gate::define('post.moderate', [Policies\PostPolicy::class, 'moderate']);
    }
}
