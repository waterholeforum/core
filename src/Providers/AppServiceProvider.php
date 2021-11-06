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
        // Blade::directive('fluent', function ($expression) {
        /*    return '<?php echo fluent('.$expression.'); ?>';*/
        // });
        //

        // Paginator::useBootstrap();

        // Paginator::defaultView('pagination');
        // Paginator::defaultSimpleView('pagination-simple');
    }
}
