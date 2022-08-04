<?php

namespace Waterhole\Extend\Concerns;

trait UnorderedList
{
    private static array $items = [];

    /**
     * Add an item to the list.
     */
    public static function add(string $key, $content): void
    {
        static::$items[$key] = $content;
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
    public static function get(string $key)
    {
        return static::$items[$key] ?? null;
    }

    /**
     * Get the keys that have been registered.
     */
    public static function keys(): array
    {
        return array_keys(static::$items);
    }

    /**
     * Get the resulting list.
     */
    public static function build(): array
    {
        return static::$items;
    }
}
