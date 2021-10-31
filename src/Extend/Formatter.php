<?php

namespace Waterhole\Extend;

use Closure;
use Illuminate\Contracts\Foundation\Application;

/**
 * ```php
 * Formatter::rendering()
 * ```
 */
class Formatter
{
    private Closure $register;

    private function __construct()
    {
    }

    public static function configure(callable $callback): static
    {
        $instance = new static();

        $instance->register = function (\Waterhole\Formatter\Formatter $formatter) use ($callback) {
            $formatter->configure($callback);
        };

        return $instance;
    }

    public static function parsing(callable $callback): static
    {
        $instance = new static();

        $instance->register = function (\Waterhole\Formatter\Formatter $formatter) use ($callback) {
            $formatter->parsing($callback);
        };

        return $instance;
    }

    public static function rendering(callable $callback): static
    {
        $instance = new static();

        $instance->register = function (\Waterhole\Formatter\Formatter $formatter) use ($callback) {
            $formatter->rendering($callback);
        };

        return $instance;
    }

    public function register(Application $app)
    {
        $app->resolving('fori.formatter', function (Formatter $formatter) {
            ($this->register)($formatter);
        });
    }

    public function onEnable(Application $app)
    {
        $app->make('fori.formatter')->flush();
    }

    public function onDisable(Application $app)
    {
        $app->make('fori.formatter')->flush();
    }
}
