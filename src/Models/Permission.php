<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class Permission extends Model
{
    public $timestamps = false;

    public function scope(): MorphTo
    {
        return $this->morphTo();
    }

    public function recipient(): MorphTo
    {
        return $this->morphTo();
    }

    public function newCollection(array $models = []): PermissionCollection
    {
        return new PermissionCollection($models);
    }
}
