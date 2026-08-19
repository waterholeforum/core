<?php

namespace Waterhole\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * A filter that ...
 */
class Trash extends Filter
{
    public const EXCLUDE_TRASHED_SCOPE = 'excludeTrashed';

    public bool $isSystem = true;
    public ?string $icon = 'tabler-trash';

    public function label(): string
    {
        return __('waterhole::forum.filter-trash');
    }

    public function apply(Builder $query): void
    {
        $query
            ->withoutGlobalScope(static::EXCLUDE_TRASHED_SCOPE)
            ->onlyTrashed()
            ->latest('deleted_at');
    }
}
