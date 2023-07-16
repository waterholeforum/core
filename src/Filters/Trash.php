<?php

namespace Waterhole\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * A filter that ...
 */
class Trash extends Filter
{
    public const EXCLUDE_TRASHED_SCOPE = 'excludeTrashed';

    public function label(): string
    {
        return __('waterhole::forum.filter-trash');
    }

    public function apply(Builder $query): void
    {
        $query
            ->withoutGlobalScope('withoutPinned')
            ->withoutGlobalScope(static::EXCLUDE_TRASHED_SCOPE)
            ->onlyTrashed();
    }
}
