<?php

namespace Waterhole\Extend\Support;

/**
 * Ordered list for extenders where item positions matter.
 *
 * Use this to register items with explicit ordering helpers.
 */
class OrderedList
{
    private array $items = [];
    private ?array $sortedItems = null;

    /**
     * Add an item to the list.
     */
    public function add($content = null, ?string $key = null, int $position = 0): static
    {
        $this->items[$key ?? uniqid()] = compact('content', 'position');
        $this->sortedItems = null;

        return $this;
    }

    /**
     * Replace an existing item in the list.
     */
    public function replace(string $key, $content): static
    {
        if (isset($this->items[$key])) {
            $this->items[$key]['content'] = $content;
            $this->sortedItems = null;
        }

        return $this;
    }

    /**
     * Remove an item from the list.
     */
    public function remove(string $key): static
    {
        unset($this->items[$key]);
        $this->sortedItems = null;

        return $this;
    }

    /**
     * Get an item's content.
     */
    public function get(string $key): mixed
    {
        return $this->items[$key]['content'] ?? null;
    }

    /**
     * Get an item's position.
     */
    public function getPosition(string $key): ?int
    {
        return $this->items[$key]['position'] ?? null;
    }

    /**
     * Get the resulting list in order.
     */
    public function items(): array
    {
        if ($this->sortedItems !== null) {
            return $this->sortedItems;
        }

        return $this->sortedItems = collect($this->items)
            ->sortBy('position')
            ->map(fn($item) => $item['content'])
            ->all();
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
