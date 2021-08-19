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

use Closure;

trait ManagesClasses
{
    private static array $callbacks = [];

    private Closure $callback;

    public function __construct(Closure $callback)
    {
        $this->callback = $callback;
    }

    public function register()
    {
        static::$callbacks[] = $this->callback;
    }

    public static function getClasses(...$params): string
    {
        $classes = static::formatClasses(static::defaultClasses(...$params));

        foreach (static::$callbacks as $callback) {
            $classes = array_merge($classes, static::formatClasses($callback(...$params)));
        }

        return implode(' ', array_filter(array_keys(array_filter($classes))));
    }

    private static function formatClasses($classes): array
    {
        if (is_string($classes)) {
            return array_fill_keys(explode(' ', $classes), true);
        }

        foreach ($classes as $k => $v) {
            if (is_integer($k)) {
                $classes += static::formatClasses($v);
                unset($classes[$k]);
            }
        }

        return $classes;
    }

    protected static function defaultClasses(...$params): array
    {
        return [];
    }
}
