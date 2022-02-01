<?php

namespace Waterhole;

use Illuminate\Foundation\Application;

abstract class Waterhole
{
    const VERSION = '0.1.0-dev';

    private static array $extenders = [];

    public static function extend(array $extenders): void
    {
        static::$extenders = array_merge(static::$extenders, $extenders);
    }

    public static function applyExtenders(Application $app, string $method): void
    {
        foreach (static::$extenders as $extender) {
            if (method_exists($extender, $method)) {
                $extender->$method($app);
            }
        }
    }

    public static function isForumRoute(): bool
    {
        return str_starts_with(request()->path(), config('waterhole.forum.path'));
    }

    public static function isAdminRoute(): bool
    {
        return str_starts_with(request()->path(), config('waterhole.admin.path'));
    }
}
