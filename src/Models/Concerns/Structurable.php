<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Waterhole\Models\Structure;

trait Structurable
{
    public static function bootStructurable()
    {
        static::created(function (Model $model) {
            $model->structure()->create([
                'position' => Structure::whereNull('parent_id')->max('position'),
            ]);
        });

        static::deleted(function (Model $model) {
            $model->structure()->delete();
        });
    }

    public function structure(): MorphOne
    {
        return $this->morphOne(Structure::class, 'content');
    }
}
