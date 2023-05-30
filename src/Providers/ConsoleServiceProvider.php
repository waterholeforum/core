<?php

namespace Waterhole\Providers;

use Illuminate\Console\Application as Artisan;
use Illuminate\Support\ServiceProvider;
use Waterhole\Console;

class ConsoleServiceProvider extends ServiceProvider
{
    protected array $commands = [
        Console\CacheClearCommand::class,
        Console\InstallCommand::class,
        Console\MakeExtensionCommand::class,
        Console\ReformatCommand::class,
    ];

    public function boot()
    {
        Artisan::starting(function ($artisan) {
            $artisan->resolveCommands($this->commands);
        });
    }
}
