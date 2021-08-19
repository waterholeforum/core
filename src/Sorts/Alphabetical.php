<?php

namespace Waterhole\Sorts;

use Illuminate\Database\Eloquent\Builder;

class Alphabetical extends Sort
{
    public function name(): string
    {
        return 'Alphabetical';
    }

    public function description(): string
    {
        return 'Description';
    }

    public function apply($query): void
    {
        $query->orderBy('title');
    }
}
