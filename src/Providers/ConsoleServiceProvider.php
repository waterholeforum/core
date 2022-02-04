<?php

namespace Waterhole\Providers;

use Illuminate\Console\Application as Artisan;
use Illuminate\Support\ServiceProvider;
use Waterhole\Console\Commands;

class ConsoleServiceProvider extends ServiceProvider
{
    protected array $commands = [
        Commands\FormatterClearCommand::class,
        Commands\InstallCommand::class,
    ];

    public function boot()
    {
        Artisan::starting(function ($artisan) {
            $artisan->resolveCommands($this->commands);
        });
    }
}
