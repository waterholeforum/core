<?php

namespace Waterhole\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Waterhole\Extend\Assets\Script;
use Waterhole\Extend\Assets\Stylesheet;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'cache:clearing' => [[Script::class, 'flush'], [Stylesheet::class, 'flush']],
    ];
}
