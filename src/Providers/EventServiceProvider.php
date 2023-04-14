<?php

namespace Waterhole\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Waterhole\Extend;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [SendEmailVerificationNotification::class],
        'cache:clearing' => [[Extend\Script::class, 'flush'], [Extend\Stylesheet::class, 'flush']],
    ];
}
