<?php

namespace Waterhole\Providers;

use Illuminate\Support\ServiceProvider;

abstract class ExtensionServiceProvider extends ServiceProvider
{
    private array $extenders;

    abstract public function extenders(): array;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->extenders = $this->extenders();
    }

    public function register(): void
    {
        $this->applyExtenders('register');
    }

    public function boot()
    {
        $this->applyExtenders('boot');
    }

    private function applyExtenders(string $method): void
    {
        foreach ($this->extenders as $extender) {
            if (method_exists($extender, $method)) {
                $extender->$method($this->app);
            }
        }
    }
}
