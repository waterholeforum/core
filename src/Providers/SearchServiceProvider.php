<?php

namespace Waterhole\Providers;

use Illuminate\Support\ServiceProvider;
use Waterhole\Search\EngineInterface;

class SearchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($engine = config('waterhole.system.search_engine')) {
            $this->app->bind(EngineInterface::class, $engine);
        }
    }
}
