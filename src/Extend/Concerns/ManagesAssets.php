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

trait ManagesAssets
{
    private string $group;
    private $source;

    public function __construct(string $group, $source)
    {
        $this->group = $group;
        $this->source = $source;
    }

    public function register(): void
    {
        static::$assets[$this->group][] = $this->source;
    }

    public static function urls(array $groups): array
    {
        $urls = [];

        foreach ($groups as $group) {
            if (empty(static::$assets[$group])) {
                continue;
            }

            $urls = array_merge($urls, self::compile(static::getAssets($group), $group));
        }

        return $urls;
    }

    abstract public static function compile(array $assets, string $group): array;

    private static function getAssets(string $group): array
    {
        return static::$assets[$group] ?? [];
    }
}
