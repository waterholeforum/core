<?php

namespace Waterhole\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * A filter that shows items that the user is ignoring, with the most recently
 * ignored at the top.
 */
class Ignoring extends Filter
{
    public const EXCLUDE_IGNORED_SCOPE = 'excludeIgnored';

    public function label(): string
    {
        return __('waterhole::forum.filter-ignoring');
    }

    public function apply(Builder $query): void
    {
        $query
            ->withoutGlobalScope(static::EXCLUDE_IGNORED_SCOPE)
            ->ignoring()
            ->leftJoinRelationship('userState')
            ->latest('followed_at');
    }
}
