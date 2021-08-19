<?php

namespace Waterhole\Sorts;

use Illuminate\Database\Eloquent\Builder;

class Oldest extends Sort
{
    public function name(): string
    {
        return 'Oldest';
    }

    public function description(): string
    {
        return 'Description';
    }

    public function apply($query): void
    {
        $query->oldest();
    }
}
