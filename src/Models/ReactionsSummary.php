<?php

namespace Waterhole\Models;

use Illuminate\Support\Collection;

class ReactionsSummary
{
    public function __construct(private readonly Collection $rows)
    {
    }

    public function totalCount(): int
    {
        return $this->rows->sum('count');
    }

    public function count(ReactionType $reactionType): int
    {
        return $this->row($reactionType)?->count ?? 0;
    }

    public function userReacted(ReactionType $reactionType): bool
    {
        return (bool) ($this->row($reactionType)?->user_reacted ?? false);
    }

    private function row(ReactionType $reactionType): object|null
    {
        return $this->rows->firstWhere('reaction_type_id', $reactionType->getKey());
    }
}
