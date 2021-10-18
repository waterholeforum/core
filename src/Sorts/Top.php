<?php

namespace Waterhole\Sorts;

use Waterhole\Models\Post;

class Top extends Sort
{
    const PERIODS = ['year', 'quarter', 'month', 'week', 'day'];

    public function name(): string
    {
        return 'Top';
    }

    public function description(): string
    {
        return 'Description';
    }

    public function apply($query): void
    {
        $query->orderByDesc('score')
            ->orderByDesc($query->getModel() instanceof Post ? 'comment_count' : 'reply_count');

        if ($period = $this->currentPeriod()) {
            $query->whereRaw('created_at > DATE_SUB(NOW(), INTERVAL 1 '.strtoupper($period).')');
        }
    }

    public function currentPeriod(): ?string
    {
        if (in_array($period = request()->query('period'), static::PERIODS)) {
            return $period;
        }

        return null;
    }
}
