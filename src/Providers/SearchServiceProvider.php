<?php

namespace Waterhole\Providers;

use Illuminate\Support\ServiceProvider;
use Waterhole\Search\EngineInterface;
use Waterhole\Search\FullTextSearchEngine;

class SearchServiceProvider extends ServiceProvider
{
    public function register()
    {
        $engine = config('waterhole.system.search_engine');

        if ($engine === 'full_text') {
            $this->app->bind(EngineInterface::class, FullTextSearchEngine::class);
        }
    }
}
