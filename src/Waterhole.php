<?php

namespace Waterhole;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Waterhole\Models\PermissionCollection;

abstract class Waterhole
{
    public const VERSION = '0.5.0';

    public static function isWaterholeRoute(): bool
    {
        return Route::currentRouteNamed('waterhole.*');
    }

    public static function isForumRoute(): bool
    {
        return static::isWaterholeRoute() && !static::isCpRoute() && !static::isApiRoute();
    }

    public static function isCpRoute(): bool
    {
        return Route::currentRouteNamed('waterhole.cp.*');
    }

    public static function isApiRoute(): bool
    {
        return Route::currentRouteNamed('waterhole.api.*');
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
