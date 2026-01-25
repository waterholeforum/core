<?php

namespace Waterhole\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * A filter that shows items that the user is following, with the most recently
 * followed at the top.
 */
class Following extends Filter
{
    public function label(): string
    {
        return __('waterhole::forum.filter-following');
    }

    public function apply(Builder $query): void
    {
        $query->following()->leftJoinRelation('userState')->latest('followed_at');
    }
}
