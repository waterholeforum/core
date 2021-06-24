<?php

namespace Waterhole\Providers;

use Waterhole\Models\Extension;
use Illuminate\Support\ServiceProvider;
use Waterhole\Waterhole;

class ExtensionServiceProvider extends ServiceProvider
{
    public function register()
    {
        // $this->app->instance('waterhole.extensions', [
        //     new Extension('tobyz/waterhole-hello-world')
        // ]);
        //
        // foreach ($this->app['waterhole.extensions'] as $extension) {
        //     $extension->extend($this->app, 'register');
        // }

        Waterhole::applyExtenders($this->app, 'register');
    }

    public function boot()
    {
        // foreach ($this->app['waterhole.extensions'] as $extension) {
        //     $extension->extend($this->app, 'boot');
        // }

        Waterhole::applyExtenders($this->app, 'boot');
    }
}
