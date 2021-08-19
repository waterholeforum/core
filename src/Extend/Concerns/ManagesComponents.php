<?php

namespace Waterhole\Extend\Concerns;

use Illuminate\Support\Collection;

trait ManagesComponents
{
    private static array $components = [];

    private string $component;
    private int $position;

    public function __construct(string $component, int $position = 0)
    {
        $this->component = $component;
        $this->position = $position;
    }

    public function register(): void
    {
        static::$components[$this->position][] = $this->component;
    }

    protected static function defaultComponents(): array
    {
        return [];
    }

    public static function getComponents(): Collection
    {
        $defaults = array_map(function ($components) {
            return is_array($components) ? $components : [$components];
        }, static::defaultComponents());

        $components = array_replace_recursive($defaults, static::$components);

        return collect($components)->sortKeys()->flatten();
    }

    public static function clearComponents(): void
    {
        static::$components = [];
    }

    public static function getInstances(): Collection
    {
        return static::getComponents()->map(fn($class) => app($class));
    }
}
