<?php

namespace Waterhole\Extend\Concerns;

use StableSort\StableSort;

trait ManagesComponents
{
    private static array $components = [];

    private string $component;
    private int $position;
    private bool $remove;

    public function __construct(string $component, int $position = 0)
    {
        $this->component = $component;
        $this->position = $position;
    }

    public function register(): void
    {
        if ($this->remove) {
            unset(static::$components[$this->component]);
        } else {
            static::$components[$this->component] = $this->position;
        }
    }

    public function remove(): void
    {
        $this->remove = true;
    }

    protected static function defaultComponents(): array
    {
        return [];
    }

    public static function getComponents(): array
    {
        $components = array_merge(static::defaultComponents(), static::$components);

        StableSort::asort($components);

        return array_keys($components);
    }
}
