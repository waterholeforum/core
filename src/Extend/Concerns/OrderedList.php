<?php

namespace Waterhole\Extend\Concerns;

trait OrderedList
{
    private static array $items = [];
    private static array $built;

    /**
     * Add an item to the list.
     */
    public static function add($content = null, int $position = 0, ?string $key = null): void
    {
        static::$items[$key ?? uniqid()] = compact('content', 'position');
    }

    /**
     * Replace an existing item in the list.
     */
    public static function replace(string $key, $content): void
    {
        if (isset(static::$items[$key])) {
            static::$items[$key]['content'] = $content;
        }
    }

    /**
     * Remove an item from the list.
     */
    public static function remove(string $key): void
    {
        unset(static::$items[$key]);
    }

    /**
     * Get an item in the list.
     */
    public static function get(string $key): ?array
    {
        return static::$items[$key] ?? null;
    }

    /**
     * Get the resulting list in order.
     */
    public static function build(): array
    {
        return static::$built ??= collect(static::$items)
            ->sortBy('position')
            ->map(fn($item) => $item['content'])
            ->all();
    }
}
