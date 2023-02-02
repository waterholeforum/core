<?php

namespace Waterhole\Providers;

use BladeUI\Icons\Factory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;
use Waterhole\Models;
use Waterhole\Notifications\DatabaseChannel;

class AppServiceProvider extends ServiceProvider
{
    protected array $configFiles = [
        'admin',
        'auth',
        'design',
        'forum',
        'system',
        'uploads',
        'users',
    ];

    public function register()
    {
        collect($this->configFiles)->each(function ($config) {
            $this->mergeConfigFrom(__DIR__ . "/../../config/$config.php", "waterhole.$config");
        });

        $this->callAfterResolving(Factory::class, function (Factory $factory) {
            $factory->add('waterhole', [
                'path' => __DIR__ . '/../../resources/icons',
                'prefix' => 'waterhole',
            ]);
        });
    }

    public function boot()
    {
        Relation::morphMap([
            'channel' => Models\Channel::class,
            'comment' => Models\Comment::class,
            'group' => Models\Group::class,
            'page' => Models\Page::class,
            'post' => Models\Post::class,
            'structureHeading' => Models\StructureHeading::class,
            'structureLink' => Models\StructureLink::class,
            'taxonomy' => Models\Taxonomy::class,
            'user' => Models\User::class,
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        collect($this->configFiles)->each(function ($config) {
            $this->publishes(
                [__DIR__ . "/../../config/$config.php" => config_path("waterhole/$config.php")],
                'waterhole-config',
            );
        });

        // Override the notifications database channel with our own instance.
        // This is necessary to extract special fields from the notification
        // data array, and assign them to real columns in the database so that
        // they can be used to power relationships.
        resolve(ChannelManager::class)->extend('database', function () {
            return new DatabaseChannel();
        });
    }
}
