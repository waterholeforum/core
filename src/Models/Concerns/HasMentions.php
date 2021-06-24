<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Waterhole\Models\User;

trait HasMentions
{
    public function mentions(): MorphMany
    {
        return $this->morphMany(User::class, 'mentions');
    }
}
