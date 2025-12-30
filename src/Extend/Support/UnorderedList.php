<?php

namespace Waterhole\Extend\Support;

/**
 * List for extenders where order does not matter.
 *
 * Use this to register items where ordering is not important.
 */
class UnorderedList
{
    private array $items = [];

    /**
     * Add an item to the list.
     */
    public function add(?string $key = null, $content = null): static
    {
        $this->items[$key ?? uniqid()] = $content;

        return $this;
    }

    /**
     * Remove an item from the list.
     */
    public function remove(string $key): static
    {
        unset($this->items[$key]);

        return $this;
    }

    /**
     * Get an item in the list.
     */
    public function get(string $key): mixed
    {
        return $this->items[$key] ?? null;
    }

    /**
     * Get whether an item is present in the list.
     */
    public function has(string $key): bool
    {
        return isset($this->items[$key]);
    }

    /**
     * Get the resulting list.
     */
    public function items(): array
    {
        return $this->items;
    }

    /**
     * Get the keys that have been registered.
     */
    public function keys(): array
    {
        return array_keys($this->items);
    }

    /**
     * Get the resulting list.
     */
    public function values(): array
    {
        return array_values($this->items);
    }
}
