<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Waterhole\Models\User;

trait HasLikes
{
    public function likedBy(): MorphToMany
    {
        return $this->morphToMany(User::class, 'content', 'likes')->withPivot('created_at');
    }

    public function refreshLikeMetadata(): static
    {
        $this->score = $this->likedBy()->count();

        return $this;
    }
}
