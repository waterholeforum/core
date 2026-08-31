<?php

namespace Waterhole\Filters;

use Illuminate\Database\Eloquent\Builder;
use Waterhole\Models\Channel;

/**
 * A filter that shows unanswered posts, ordered by newest first.
 */
class Unanswered extends Filter
{
    public function label(): string
    {
        return __('waterhole::forum.filter-unanswered');
    }

    public function availableFor(?Channel $channel = null): bool
    {
        return !$channel || $channel->answerable;
    }

    public function apply(Builder $query): void
    {
        $query
            ->whereRelation('channel', 'answerable', true)
            ->whereNull('posts.answer_id')
            ->latest('posts.created_at');
    }
}
