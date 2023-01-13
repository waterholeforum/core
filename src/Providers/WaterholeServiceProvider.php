<?php

namespace Waterhole\Providers;

use Illuminate\Support\AggregateServiceProvider;
use Waterhole\Taxonomy\TaxonomyServiceProvider;

class WaterholeServiceProvider extends AggregateServiceProvider
{
    protected $providers = [
        AppServiceProvider::class,
        AuthServiceProvider::class,
        BroadcastServiceProvider::class,
        ConsoleServiceProvider::class,
        EventServiceProvider::class,
        FormatterServiceProvider::class,
        TaxonomyServiceProvider::class,
        RouteServiceProvider::class,
        TranslationServiceProvider::class,
        TwemojiServiceProvider::class,
        ViewServiceProvider::class,
    ];
}
