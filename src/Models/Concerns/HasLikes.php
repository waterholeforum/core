<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Waterhole\Models\User;

trait HasLikes
{
    public function likedBy(): MorphMany
    {
        return $this->morphMany(User::class, 'likes')->withTimestamps();
    }
}
