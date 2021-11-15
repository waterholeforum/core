<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Structure extends Model
{
    use HasRecursiveRelationships;

    protected $table = 'structure';

    public $timestamps = false;

    protected $casts = [
        'data' => 'json',
    ];

    public function content(): MorphTo
    {
        return $this->morphTo();
    }
}
