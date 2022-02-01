<?php

namespace Waterhole\Extend\Concerns;

/**
 *
 */
trait Set
{
    private static array $values = [];

    /**
     * Add a value to the set.
     */
    public static function add(...$values): void
    {
        foreach ($values as $value) {
            if (! in_array($value, static::$values)) {
                static::$values[] = $value;
            }
        }
    }

    /**
     * Remove a value from the set.
     */
    public static function remove(...$values): void
    {
        static::$values = array_filter(
            static::$values,
            fn($value) => ! in_array($value, $values)
        );
    }

    /**
     * Determines if the set contains a value.
     */
    public static function contains(...$values): bool
    {
        return ! array_diff($values, static::$values);
    }

    /**
     * Get the values as an array.
     */
    public static function values(): array
    {
        return static::$values;
    }
}
