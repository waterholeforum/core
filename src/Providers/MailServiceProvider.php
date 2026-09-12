<?php

declare(strict_types=1);

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
