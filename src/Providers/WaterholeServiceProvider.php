<?php

namespace Waterhole\Providers;

use Illuminate\Support\AggregateServiceProvider;

class WaterholeServiceProvider extends AggregateServiceProvider
{
    protected $providers = [
        ApiServiceProvider::class,
        AppServiceProvider::class,
        AuthServiceProvider::class,
        BroadcastServiceProvider::class,
        ConsoleServiceProvider::class,
        EventServiceProvider::class,
        FormatterServiceProvider::class,
        MailServiceProvider::class,
        RouteServiceProvider::class,
        SearchServiceProvider::class,
        TranslationServiceProvider::class,
        ViewServiceProvider::class,
    ];
}
