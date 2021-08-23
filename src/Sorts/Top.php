<?php

namespace Waterhole\Sorts;

use Waterhole\Models\Post;

class Top extends Sort
{
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
    }
}
