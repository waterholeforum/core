<?php

namespace Waterhole\Feed;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use RuntimeException;
use UnexpectedValueException;
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

        if ($query = $this->request->query('filter')) {
            $currentFilter = $this->filters->first(
                fn(Filter $filter) => $filter->handle() === $query,
            );

            if (!$currentFilter) {
                abort(404);
            }

            $this->currentFilter = $currentFilter;
        } else {
            $this->currentFilter = $this->filters[0];
        }
    }

    /**
     * Get the paginated feed items.
     */
    public function items(): CursorPaginator
    {
        $query = $this->query->clone();

        $this->currentFilter->apply($query);

        // Crawlers can sometimes end up remembering an invalid pagination
        // cursor, which will cause a 500 error - make it a 400 error instead.
        try {
            return $query->cursorPaginate();
        } catch (UnexpectedValueException) {
            abort(400);
        }
    }
}
