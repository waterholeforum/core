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
    private ?string $model;
    private string $key;
    private string $ability;

    public function __construct(string $model = null, string $key = 'id', string $ability = 'view')
    {
        $this->model = $model;
        $this->key = $key;
        $this->ability = $ability;
    }

    public function apply(Builder $builder, Model $model)
    {
        if (app()->runningInConsole()) {
            return;
        }

        /** @var User $user */
        $user = Auth::user();
        $model = $this->model ?: $model;

        // If the list of IDs is null, then the user must be an administrator,
        // and therefore there are no restrictions to apply.
        if (!is_null($ids = $model::allPermitted($user, $this->ability))) {
            $builder->whereIn($this->key, $ids);
        }
    }
}
