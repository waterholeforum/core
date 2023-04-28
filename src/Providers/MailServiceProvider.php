<?php

namespace Waterhole\Providers;

use Illuminate\Support\ServiceProvider;
use Waterhole\Mail\Markdown;

class MailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Markdown::class);
    }
}
