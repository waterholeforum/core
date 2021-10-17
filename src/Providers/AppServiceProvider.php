<?php

namespace Waterhole\Providers;

use BladeUI\Icons\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use PragmaRX\Yaml\Package\Facade as Yaml;

class AppServiceProvider extends ServiceProvider
{
    protected array $configFiles = [
        'forum', 'system', 'users'
    ];

    public function register()
    {
        collect($this->configFiles)->each(function ($config) {
            $this->mergeConfigFrom(__DIR__."/../../config/$config.php", "waterhole.$config");
        });

        $this->callAfterResolving(Factory::class, function (Factory $factory) {
            $factory->add('waterhole', [
                'path' => __DIR__.'/../../resources/svg',
                'prefix' => 'waterhole',
            ]);
        });

        // $this->app->bind(
        //     \Laravel\Scout\Builder::class,
        //     Builder::class
        // );
        //
        // $this->app->resolving(EngineManager::class, function (EngineManager $manager) {
        //     $manager->extend('mysql', function () {
        //         return new MySqlEngine;
        //     });
        // });
        //
        // $this->app->instance('waterhole.discussionListQuery', [
        //     'include' => 'user,lastCommentBy,subscription,bookmark,category.ancestors,reactionCounts.reactionType'
        // ]);
        //
        // $this->app->instance('waterhole.discussionListDefaultFilter', 'latest');
        //
        // $this->app->instance('waterhole.discussionQuery', ['include' => 'category.ancestors,bookmark,subscription,user,lastCommentBy,reactionCounts.reactionType']);
        // $this->app->instance('waterhole.postsQuery', ['include' => 'discussion.category.ancestors,user.groups,reactions.reactionType,highlights,flags.user']);
        // $this->app->instance('waterhole.userQuery', ['include' => 'groups']);
        //
        // $this->app->instance('waterhole.userPostsFilters', [
        //     'latest' => ['sort' => '-createdAt'],
        //     'top' => ['sort' => '-reactionCount'],
        // ]);
        //
        // $this->app->instance('waterhole.userPostsDefaultFilter', 'latest');
        //
        // $this->app->instance('waterhole.userDiscussionsDefaultFilter', 'new');
        //
        // $this->app->instance('waterhole.categoryListQuery', [
        //     'include' => 'subscription,latestDiscussions,latestDiscussions.lastCommentBy,children',
        //     'filter' => ['root' => 1]
        // ]);
        //
        // $this->app->singleton('waterhole.reactionTypes', function () {
        //     return waterhole_api(new ServerRequest('GET', 'reactionTypes'));
        // });
    }

    public function boot()
    {
        Model::preventLazyLoading();

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        collect($this->configFiles)->each(function ($config) {
            $this->publishes([__DIR__."/../../config/$config.php" => config_path("waterhole/$config.php")], 'waterhole');
        });

        // Override the notifications database channel with our own instance.
        // This is necessary to extract special fields from the notification
        // data array, and assign them to real columns in the database so that
        // they can be used to power relationships.
        // resolve(ChannelManager::class)->extend('database', function () {
        //     return new DatabaseChannel;
        // });
        //

        //
        // Blade::directive('fluent', function ($expression) {
        /*    return '<?php echo fluent('.$expression.'); ?>';*/
        // });
        //

        Paginator::useBootstrap();

        // Paginator::defaultView('pagination');
        // Paginator::defaultSimpleView('pagination-simple');
    }
}
