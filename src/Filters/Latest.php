<?php

namespace Waterhole\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * A filter that sorts results by most recently created.
 */
class Latest extends Filter
{
    public function label(): string
    {
        return 'Latest';
    }

    public function apply(Builder $query): void
    {
        $query->latest();
    }
}
