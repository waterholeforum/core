<?php

namespace Waterhole\Extend\Support;

use Closure;

/**
 * Support class for registering extender callbacks.
 *
 * Use this to collect callbacks and invoke them in order.
 */
class Callbacks
{
    private array $callbacks = [];

    /**
     * Add a callback to register routes.
     */
    public function register(Closure $callback): static
    {
        $this->callbacks[] = $callback;

        return $this;
    }

    public function execute(): void
    {
        foreach ($this->callbacks as $callback) {
            $callback();
        }
    }
}
