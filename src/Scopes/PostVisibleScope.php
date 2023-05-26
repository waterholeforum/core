<?php

namespace Waterhole\Scopes;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Waterhole\Extend\PostScopes;
use Waterhole\Models\User;

class PostVisibleScope implements Scope
{
    public function __construct(private null|User|Closure $user)
    {
    }

    public function apply(Builder $builder, Model $model)
    {
        $user = $this->user instanceof Closure ? ($this->user)() : $this->user;

        if (app()->runningInConsole() && !$user) {
            return;
        }

        foreach (PostScopes::build() as $scope) {
            $scope($builder, $user);
        }
    }
}
