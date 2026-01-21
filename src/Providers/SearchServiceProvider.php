<?php

namespace Waterhole\Providers;

use Illuminate\Support\ServiceProvider;
use Waterhole\Search\EngineInterface;
use Waterhole\Search\DatabaseSearchEngine;

class SearchServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(EngineInterface::class, DatabaseSearchEngine::class);
    }
}
