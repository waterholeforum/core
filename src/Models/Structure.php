<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class Structure extends Model
{
    protected $table = 'structure';

    public $timestamps = false;

    public function content(): MorphTo
    {
        return $this->morphTo();
    }
}
