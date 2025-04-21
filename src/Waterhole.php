<?php

namespace Waterhole;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Waterhole\Models\PermissionCollection;

abstract class Waterhole
{
    public const VERSION = '0.4.11';

    public static function isForumRoute(): bool
    {
        return str_starts_with(Request::path(), Config::get('waterhole.forum.path'));
    }

    public static function isCpRoute(): bool
    {
        return str_starts_with(Request::path(), Config::get('waterhole.cp.path'));
    }

    public static function isWaterholeRoute(): bool
    {
        return static::isForumRoute() || static::isCpRoute();
    }

    public static function permissions(): PermissionCollection
    {
        return app('waterhole.permissions');
    }

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
            ->reject(fn($file) => in_array(str_replace('.php', '', basename($file)), $ran))
            ->isNotEmpty();
    }
}
