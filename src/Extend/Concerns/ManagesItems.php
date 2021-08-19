<?php

/*
 * This file is part of Waterhole.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Waterhole\Extend\Concerns;

trait ManagesItems
{
    private static array $items = [];

    private string $key;
    private $value;

    public function __construct(string $key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function register(): void
    {
        static::$items[$this->key] = $this->value;
    }

    protected static function defaultItems(): array
    {
        return [];
    }

    public static function getItems()
    {
        return array_merge(static::defaultItems(), static::$items);
    }
}
