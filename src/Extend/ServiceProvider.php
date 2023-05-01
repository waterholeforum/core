<?php

namespace Waterhole\Extend;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    protected static bool $registered = false;

    public function register(): void
    {
        if (!static::$registered) {
            $this->extend();

            static::$registered = true;
        }
    }

    public function extend(): void
    {
        //
    }
}
