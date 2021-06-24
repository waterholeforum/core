<?php

namespace Waterhole\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * A filter that sorts results by most recently active.
 */
class Latest extends Filter
{
    public function label(): string
    {
        return __('waterhole::forum.filter-latest');
    }

    public function apply(Builder $query): void
    {
        $query->latest('last_activity_at');
    }
}
