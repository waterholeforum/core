<?php

namespace Waterhole\Sorts;

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
        $query->orderByDesc('score');
    }
}
