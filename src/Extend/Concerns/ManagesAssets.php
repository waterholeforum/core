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
    private string $file;

    public function __construct(string $group, string $file)
    {
        $this->group = $group;
        $this->file = $file;
    }

    public function register(): void
    {
        static::$assets[$this->group][] = $this->file;
    }

    public static function urls(array $groups): array
    {
        $urls = [];

        foreach ($groups as $group) {
            if (empty(static::$assets[$group])) {
                continue;
            }

            $urls = array_merge($urls, static::compile(static::$assets[$group], $group));
        }

        return $urls;
    }

    abstract public static function compile(array $assets, string $group): array;
}
