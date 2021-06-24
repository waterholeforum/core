<?php

namespace Waterhole;

use Illuminate\Support\Collection;
use Waterhole\Models\PermissionCollection;

abstract class Waterhole
{
    public const VERSION = '0.1.0-dev';

    public static function hasPendingMigrations(): bool
    {
        $migrator = app('migrator');
        $files = $migrator->getMigrationFiles(__DIR__ . '/../database/migrations');
        $repository = $migrator->getRepository();

        if (!$repository->repositoryExists()) {
            return true;
        }

        $ran = $repository->getRan();

        return Collection::make($files)
            ->reject(function ($file) use ($ran) {
                return in_array(str_replace('.php', '', basename($file)), $ran);
            })
            ->isNotEmpty();
    }

    public static function isForumRoute(): bool
    {
        return str_starts_with(request()->path(), config('waterhole.forum.path'));
    }

    public static function isAdminRoute(): bool
    {
        return str_starts_with(request()->path(), config('waterhole.admin.path'));
    }

    public static function isWaterholeRoute(): bool
    {
        return static::isForumRoute() || static::isAdminRoute();
    }

    public static function permissions(): PermissionCollection
    {
        return app('waterhole.permissions');
    }
}
