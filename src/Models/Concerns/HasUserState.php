<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Waterhole\Models\User;

/**
 * Methods to give a model per-user state.
 */
trait HasUserState
{
    /**
     * Relationship with the state record for a user.
     *
     * Defaults to the current user when no user is specified, as is the case
     * when eager-loading the relationship.
     *
     * The model representing the user state table is derived by appending
     * `User` to the parent model name (eg. `PostUser`). Alternatively, it can
     * be specified by setting the `userStateModel` property.
     */
    public function userState(User $user = null): HasOne
    {
        $relation = $this->hasOne($this->userStateModel ?? get_class($this) . 'User');

        if ($userId = $user->id ?? Auth::id()) {
            $relation->withDefault(['user_id' => $userId]);
        }

        $relation->where($relation->qualifyColumn('user_id'), $userId);

        return $relation;
    }

    /**
     * Load the userState relationship for the given user.
     */
    public function loadUserState(User $user): static
    {
        $this->setRelation('userState', $this->userState($user)->getResults());

        return $this;
    }

    public function getRelationValue($key)
    {
        if ($key === 'userState' && !Auth::check()) {
            return null;
        }

        return parent::getRelationValue($key);
    }
}
