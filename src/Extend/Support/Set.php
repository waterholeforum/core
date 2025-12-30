<?php

namespace Waterhole\Extend\Support;

/**
 * Simple set of values for extenders that only need uniqueness.
 *
 * Use this to register unique items without ordering.
 */
class Set
{
    private array $values = [];

    /**
     * Add a value to the set.
     */
    public function add(...$values): void
    {
        foreach ($values as $value) {
            if (!in_array($value, $this->values)) {
                $this->values[] = $value;
            }
        }
    }

    /**
     * Remove a value from the set.
     */
    public function remove(...$values): void
    {
        $this->values = array_filter($this->values, fn($value) => !in_array($value, $values));
    }

    /**
     * Determines if the set contains a value.
     */
    public function contains(...$values): bool
    {
        return !array_diff($values, $this->values);
    }

    /**
     * Get the values as an array.
     */
    public function values(): array
    {
        return $this->values;
    }
}
