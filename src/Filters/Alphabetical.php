<?php

namespace Waterhole\Filters;

/**
 * A filter that sorts results by the `title` column alphabetically.
 */
class Alphabetical extends Filter
{
    public function label(): string
    {
        return 'Alphabetical';
    }

    public function apply($query): void
    {
        $query->orderBy('title');
    }
}
