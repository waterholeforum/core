<?php

namespace Waterhole\Models\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Waterhole\Models\User;

/**
 * Methods to give a model selective "visibility" for different users.
 */
trait HasVisibility
{
    private static array $visibilityScopes = [];

    /**
     * Get only the models that the given user is allowed to see.
     */
    public function scopeVisibleTo(Builder $query, ?User $user): void
    {
        foreach (static::$visibilityScopes as $scope) {
            $scope($query, $user);
        }
    }

    /**
     * Add a query scope that restricts which models a user is allowed to see.
     */
    public static function addVisibilityScope(Closure $scope): void
    {
        static::$visibilityScopes[] = $scope;
    }
}
