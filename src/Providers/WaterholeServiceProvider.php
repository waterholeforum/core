<?php

namespace Waterhole\Providers;

use Illuminate\Support\AggregateServiceProvider;

class WaterholeServiceProvider extends AggregateServiceProvider
{
    protected $providers = [
        AppServiceProvider::class,
        AuthServiceProvider::class,
        BroadcastServiceProvider::class,
        ConsoleServiceProvider::class,
        EventServiceProvider::class,
        FormatterServiceProvider::class,
        RouteServiceProvider::class,
        TranslationServiceProvider::class,
        ViewServiceProvider::class,
    ];
}
