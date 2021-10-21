<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Waterhole\Models\User;

trait HasUserState
{
    public function userState(User $user = null): HasOne
    {
        $relation = $this->hasOne($this->userStateModel ?? get_class($this).'User');

        if ($userId = $user ? $user->id : Auth::id()) {
            $relation->withDefault(['user_id' => $userId]);
        }

        $relation->where($relation->qualifyColumn('user_id'), $userId);

        return $relation;
    }
}
