<?php

namespace Waterhole\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Waterhole\Extend\Assets\Script;
use Waterhole\Extend\Assets\Stylesheet;
use Waterhole\Listeners\ReverifyInactiveUser;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'cache:clearing' => [[Script::class, 'flush'], [Stylesheet::class, 'flush']],
        Login::class => [ReverifyInactiveUser::class],
    ];
}
