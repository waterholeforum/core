<?php

namespace Waterhole\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use ReflectionClass;
use Waterhole\Models\Channel;

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
     * Whether the filter is a system view rather than a configured view.
     */
    public bool $isSystem = false;

    /**
     * The optional icon displayed alongside the filter label.
     */
    public ?string $icon = null;

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
     * Whether the filter is available in the given channel context.
     */
    public function availableFor(?Channel $channel = null): bool
    {
        return true;
    }

    /**
     * Apply the filter to the feed query builder.
     */
    abstract public function apply(Builder $query): void;
}
