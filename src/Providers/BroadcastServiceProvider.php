<?php

namespace Waterhole\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Broadcast::routes();

        require __DIR__.'/../../routes/channels.php';
    }
}
