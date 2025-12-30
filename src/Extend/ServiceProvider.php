<?php

namespace Waterhole\Extend;

use Closure;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use InvalidArgumentException;
use ReflectionFunction;

/**
 * Extension service provider.
 *
 * Adds the `extend()` helper that wires extenders into the container.
 */
class ServiceProvider extends BaseServiceProvider
{
    protected function extend(callable $callback): void
    {
        $reflection = new ReflectionFunction(Closure::fromCallable($callback));
        $parameters = $reflection->getParameters();

        if (!$parameters) {
            throw new InvalidArgumentException(
                'Extension callbacks must type-hint at least one extender.',
            );
        }

        $classes = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
                throw new InvalidArgumentException(
                    'Extension callbacks must type-hint an extender class.',
                );
            }

            $classes[] = $type->getName();
        }

        foreach ($classes as $class) {
            if (!$this->app->bound($class)) {
                $this->app->scoped($class);
            }
        }

        if (count($classes) === 1) {
            $class = $classes[0];

            $this->app->extend($class, function ($instance) use ($callback) {
                return $callback($instance) ?: $instance;
            });

            return;
        }

        $app = $this->app;
        $executed = false;

        foreach ($classes as $class) {
            $this->app->extend($class, function ($instance) use (
                $callback,
                $classes,
                $app,
                &$executed,
            ) {
                if (!$executed) {
                    $executed = true;
                    $callback(...array_map(fn($class) => $app->make($class), $classes));
                }

                return $instance;
            });
        }
    }
}
