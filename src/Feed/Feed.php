<?php

namespace Waterhole\Feed;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use RuntimeException;
use Waterhole\Filters\Filter;

/**
 * A Feed is a paginated query that can have Filters applied to it.
 */
class Feed
{
    public Collection $filters;
    public Filter $currentFilter;

    public function __construct(
        protected Request $request,
        protected Builder $query,
        array $filters,
    ) {
        $this->filters = collect($filters);

        if (!$this->filters->count()) {
            throw new RuntimeException('A feed must have at least 1 filter');
        }

        $query = $this->request->query('filter');

        $this->currentFilter = $this->filters->first(
            fn(Filter $filter) => $filter->handle() === $query,
            $this->filters[0],
        );
    }

    /**
     * Get the paginated feed items.
     */
    public function items(): CursorPaginator
    {
        $query = $this->query->clone();

        $this->currentFilter->apply($query);

        return $query->cursorPaginate();
    }
}
