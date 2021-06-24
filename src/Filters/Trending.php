<?php

namespace Waterhole\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * A filter that ...
 */
class Trending extends Filter
{
    public function label(): string
    {
        return __('waterhole::forum.filter-trending');
    }

    public function apply(Builder $query): void
    {
        $query->orderByDesc('hotness');
    }
}
