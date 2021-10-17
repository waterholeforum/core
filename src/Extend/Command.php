<?php

namespace Waterhole\Extend;

use Illuminate\Console\Application as Artisan;

class Command
{
    private string $command;

    public function __construct(string $command)
    {
        $this->command = $command;
    }

    public function register(): void
    {
        Artisan::starting(function (Artisan $artisan) {
            $artisan->resolve($this->command);
        });
    }
}
