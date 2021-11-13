<?php

namespace Waterhole;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Waterhole\Sorts\Sort;

class Feed
{
    private Request $request;
    private Builder $query;
    private Collection $sorts;
    private string $defaultSort;
    private string $defaultLayout;

    public function __construct(
        Request $request,
        Builder $query,
        array $sorts,
        string $defaultSort,
    ) {
        $this->request = $request;
        $this->query = $query;
        $this->sorts = collect($sorts);
        $this->defaultSort = $defaultSort;
    }

    public function items(): CursorPaginator
    {
        $query = $this->query->clone();

        if ($sort = $this->currentSort()) {
            $sort->apply($query);
        }

        return $query->cursorPaginate();
    }

    public function sorts(): Collection
    {
        return $this->sorts;
    }

    public function defaultSort(): string
    {
        return $this->defaultSort;
    }

    public function currentSort(): ?Sort
    {
        $query = $this->request->query('sort', $this->defaultSort());

        return $this->sorts->first(fn(Sort $sort) => $sort->handle() === $query, $this->sorts[0]);
    }
}
