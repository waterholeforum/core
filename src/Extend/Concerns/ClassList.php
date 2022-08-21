<?php

namespace Waterhole\Extend\Concerns;

trait ClassList
{
    use UnorderedList;

    /**
     * Get the resulting class list for the given model.
     */
    public static function build($model): string
    {
        $classes = array_keys(
            array_filter(array_map(fn($callback) => $callback($model), static::$items)),
        );

        return implode(' ', $classes);
    }
}
