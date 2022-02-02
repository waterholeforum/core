<?php

namespace Waterhole;

abstract class Waterhole
{
    const VERSION = '0.1.0-dev';

    public static function isForumRoute(): bool
    {
        return str_starts_with(request()->path(), config('waterhole.forum.path'));
    }

    public static function isAdminRoute(): bool
    {
        return str_starts_with(request()->path(), config('waterhole.admin.path'));
    }
}
