<?php

namespace Waterhole\Models\Concerns;

use Closure;
use Waterhole\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait HasVisibility
{
    private static array $visibilityScopes = [];

    public static function addVisibilityScope(Closure $scope): void
    {
        static::$visibilityScopes[] = $scope;
    }

    public function scopeVisibleTo(Builder $query, ?User $actor): void
    {
        foreach (static::$visibilityScopes as $scope) {
            $scope($query, $actor);
        }
    }
}
