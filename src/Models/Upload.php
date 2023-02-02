<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Upload extends Model
{
    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'content', 'attachments');
    }

    public function comments(): MorphToMany
    {
        return $this->morphedByMany(Comment::class, 'content', 'attachments');
    }
}
