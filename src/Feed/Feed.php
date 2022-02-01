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
    protected Request $request;
    protected Builder $query;
    protected Collection $filters;

    public function __construct(
        Request $request,
        Builder $query,
        array $filters
    ) {
        $this->request = $request;
        $this->query = $query;
        $this->filters = collect($filters);

        if (! $this->filters->count()) {
            throw new RuntimeException('A feed must have at least 1 filter');
        }
    }

    /**
     * Get the paginated feed items.
     */
    public function items(): CursorPaginator
    {
        $query = $this->query->clone();

        if ($filter = $this->currentFilter()) {
            $filter->apply($query);
        }

        return $query->cursorPaginate();
    }

    /**
     * Get the available filters.
     */
    public function filters(): Collection
    {
        return $this->filters;
    }

    /**
     * Get the filter that is currently active.
     */
    public function currentFilter(): Filter
    {
        $query = $this->request->query('filter', $this->filters->keys()[0]);

        return $this->filters->first(fn(Filter $filter) => $filter->handle() === $query, $this->filters[0]);
    }
}
