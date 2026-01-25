<?php

namespace Waterhole\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Waterhole\Models\User;

/**
 * Scope to restrict model visibility according to the permission system.
 *
 * Without any parameters, this scope will restrict query results to only those
 * models with IDs that the user (or their groups) has been granted permission
 * in the `permissions` table.
 *
 * The model to get IDs for, as well as the key in the current query that
 * corresponds to those IDs, can be customized so that for example, posts can
 * be filtered by their `channel_id`.
 */
class PermittedScope implements Scope
{
    public function __construct(
        private ?string $model = null,
        private string $key = 'id',
        private string $ability = 'view',
        private ?User $user = null,
    ) {}

    public function apply(Builder $builder, Model $model)
    {
        $user = $this->user ?: Auth::user();

        if (app()->runningInConsole() && !app()->runningUnitTests() && !$user) {
            return;
        }

        $model = $this->model ?: $model;

        // If the list of IDs is null, then the user must be an administrator,
        // and therefore there are no restrictions to apply.
        if (!is_null($ids = $model::allPermitted($user, $this->ability))) {
            $qualifier = $model instanceof Model ? $model : new $model();

            $builder->whereIn($qualifier->qualifyColumn($this->key), $ids);
        }
    }
}
