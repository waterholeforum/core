<?php

namespace Waterhole\Filters;

use Illuminate\Database\Eloquent\Builder;
use Waterhole\Models\Post;

/**
 * A filter that sorts results by their reaction score, with an optional
 * scoping by time period.
 */
class Top extends Filter
{
    const PERIODS = ['year', 'quarter', 'month', 'week', 'day'];

    public function label(): string
    {
        return 'Top';
    }

    public function apply(Builder $query): void
    {
        $query->orderByDesc('score');

        if ($query->getModel() instanceof Post) {
            $query->orderByDesc('comment_count');
        }

        if ($period = $this->currentPeriod()) {
            $query->whereRaw('created_at > DATE_SUB(NOW(), INTERVAL 1 '.strtoupper($period).')');
        }
    }

    /**
     * Get the currently selected time period.
     */
    public function currentPeriod(): ?string
    {
        if (in_array($period = request()->query('period'), static::PERIODS)) {
            return $period;
        }

        return null;
    }
}
