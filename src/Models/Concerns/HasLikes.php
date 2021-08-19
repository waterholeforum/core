<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Waterhole\Models\User;

trait HasLikes
{
    public function likedBy()
    {
        return $this->morphToMany(User::class, 'content', 'likes')->withPivot('created_at');
    }

    public function refreshLikeMetadata(): static
    {
        $this->score = $this->likedBy()->count();

        return $this;
    }
}
