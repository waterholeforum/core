<?php

namespace Waterhole\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * A filter that sorts results by least recently created.
 */
class Oldest extends Filter
{
    public function label(): string
    {
        return __('waterhole::forum.filter-oldest');
    }

    public function apply(Builder $query): void
    {
        $query->oldest();
    }
}
