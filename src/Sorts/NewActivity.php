<?php

namespace Waterhole\Sorts;

use Illuminate\Database\Eloquent\Builder;

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
        $query->latest('last_comment_at');
    }
}
