<?php

namespace Waterhole\Extend\Concerns;

use Illuminate\View\ComponentAttributeBag;

trait Attributes
{
    use Set;

    /**
     * Get the resulting class list for the given model.
     */
    public static function build($model): array
    {
        $attributes = new ComponentAttributeBag();

        foreach (static::values() as $callback) {
            $attributes = $attributes->merge($callback($model));
        }

        return $attributes->getAttributes();
    }
}
