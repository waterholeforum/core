<?php

namespace Waterhole\Filters;

use Illuminate\Database\Eloquent\Builder;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;

/**
 * A filter that shows posts with the most recently created answers first.
 */
class Answered extends Filter
{
    public function label(): string
    {
        return __('waterhole::forum.filter-answered');
    }

    public function availableFor(?Channel $channel = null): bool
    {
        return !$channel || $channel->answerable;
    }

    public function apply(Builder $query): void
    {
        $query
            ->whereRelation('channel', 'answerable', true)
            ->whereNotNull('posts.answer_id')
            ->orderByDesc(
                Comment::withoutGlobalScopes()
                    ->select('comments.created_at')
                    ->whereColumn('comments.id', 'posts.answer_id'),
            );
    }
}
