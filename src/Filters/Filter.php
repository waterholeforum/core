<?php

namespace Waterhole\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use ReflectionClass;

/**
 * Base class for a Filter.
 *
 * A filter is a set of filtering or sorting criteria that can be applied to a
 * feed query, like "Newest" or "Top".
 *
 * Define a new filter by extending this class and implementing the methods.
 * Use the `PostFilters` and `CommentFilters` extenders to register a filter
 * for the appropriate feed types, making them available for configuration.
 */
abstract class Filter
{
    /**
     * The handle for the filter, used in query parameters.
     */
    public function handle(): string
    {
        return Str::kebab((new ReflectionClass($this))->getShortName());
    }

    /**
     * The text label for the filter.
     */
    abstract public function label(): string;

    /**
     * Apply the filter to the feed query builder.
     */
    abstract public function apply(Builder $query): void;
}
