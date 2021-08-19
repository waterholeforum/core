<?php

namespace Waterhole\Sorts;

use Illuminate\Database\Eloquent\Builder;

class Latest extends Sort
{
    public function name(): string
    {
        return 'Latest';
    }

    public function description(): string
    {
        return 'Description';
    }

    public function apply($query): void
    {
        $query->latest();
    }
}
