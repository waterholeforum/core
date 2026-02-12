<?php

namespace Waterhole\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * A filter that shows posts with a saved draft comment for the current user.
 */
class Drafts extends Filter
{
    public function label(): string
    {
        return __('waterhole::forum.filter-drafts');
    }

    public function apply(Builder $query): void
    {
        $draftBody = $query->getModel()->userState()->getRelated()->qualifyColumn('draft_body');

        $query
            ->leftJoinRelation('userState')
            ->whereNotNull($draftBody)
            ->where($draftBody, '!=', '')
            ->latest('draft_saved_at');
    }
}
