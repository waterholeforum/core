<?php

namespace Waterhole\Extend\Support;

use Illuminate\View\ComponentAttributeBag;

/**
 * Support class for building HTML attributes from extenders.
 *
 * Use this to merge attributes from multiple extenders before rendering.
 */
class Attributes extends Set
{
    /**
     * Get the resulting class list for the given model.
     */
    public function build($model): array
    {
        $attributes = new ComponentAttributeBag();

        foreach ($this->values() as $callback) {
            $attributes = $attributes->merge($callback($model));
        }

        return $attributes->getAttributes();
    }
}
