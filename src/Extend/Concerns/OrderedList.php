<?php

namespace Waterhole\Extend\Concerns;

trait OrderedList
{
    private static array $items = [];

    public static function add(string $key, $content, int $position = 0): void
    {
        static::$items[$key] = compact('content', 'position');
    }

    public static function remove(string $key): void
    {
        unset(static::$items[$key]);
    }

    public static function get(string $key): ?array
    {
        return static::$items[$key] ?? null;
    }

    public static function build(): array
    {
        return collect(static::$items)
            ->sortBy('position')
            ->map(fn($item) => $item['content'])
            ->all();
    }
}
