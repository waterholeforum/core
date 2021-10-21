<?php

namespace Waterhole\Sorts;

class NewActivity extends Sort
{
    public function name(): string
    {
        return 'New Activity';
    }

    public function description(): string
    {
        return 'Description';
    }

    public function apply($query): void
    {
        $query->latest('last_activity_at');
    }
}
