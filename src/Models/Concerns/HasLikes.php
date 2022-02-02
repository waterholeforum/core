<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Waterhole\Models\User;

/**
 * Methods to manage "likes" on a model.
 */
trait HasLikes
{
    /**
     * Relationship with the users who have liked this model.
     */
    public function likedBy(): MorphToMany
    {
        return $this->morphToMany(User::class, 'content', 'likes')->withPivot('created_at');
    }

    /**
     * Recalculate the score from the model's likes.
     */
    public function refreshLikeMetadata(): static
    {
        $this->score = $this->likedBy()->count();

        return $this;
    }
}
