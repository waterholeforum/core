<?php

namespace Waterhole\Extend;

use Illuminate\Console\Application as Artisan;

class Console
{
    private string $command;

    public static function addCommand(string $command): static
    {
        $instance = new static();
        $instance->command = $command;
        return $instance;
    }

    public function register(): void
    {
        Artisan::starting(function (Artisan $artisan) {
            $artisan->resolve($this->command);
        });
    }
}
