<?php

namespace Waterhole\Providers;

use Illuminate\Support\AggregateServiceProvider;

class WaterholeServiceProvider extends AggregateServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        AppServiceProvider::class,
        AuthServiceProvider::class,
        LocaleServiceProvider::class,
        RouteServiceProvider::class,
        BroadcastServiceProvider::class,
        ViewServiceProvider::class,

        // ExtensionServiceProvider::class,

        // IgnitionServiceProvider::class,
        // ViewServiceProvider::class,
        // AppServiceProvider::class,
        // ConsoleServiceProvider::class,
        // CollectionsServiceProvider::class,
        // CacheServiceProvider::class,
        // FilesystemServiceProvider::class,
        // ExtensionServiceProvider::class,
        // EventServiceProvider::class,
        // \Statamic\Stache\ServiceProvider::class,
        // AuthServiceProvider::class,
        // GlideServiceProvider::class,
        // MarkdownServiceProvider::class,
        // \Statamic\Search\ServiceProvider::class,
        // \Statamic\StaticCaching\ServiceProvider::class,
        // \Statamic\Revisions\ServiceProvider::class,
        // CpServiceProvider::class,
        // ValidationServiceProvider::class,
        // RouteServiceProvider::class,
        // BroadcastServiceProvider::class,
        // \Statamic\API\ServiceProvider::class,
        // \Statamic\Git\ServiceProvider::class,
        // \Statamic\GraphQL\ServiceProvider::class,
    ];
}
