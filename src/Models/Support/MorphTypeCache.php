<?php

namespace Waterhole\Models\Support;

use Illuminate\Database\Eloquent\Relations\Relation;

final class MorphTypeCache
{
    /**
     * Get morph-mapped classes that use a given trait.
     *
     * @param class-string $trait
     * @return array<int, class-string>
     */
    public static function forTrait(string $trait): array
    {
        static $cache = [];

        return $cache[$trait] ??= collect(Relation::morphMap())
            ->filter(fn(string $class) => in_array($trait, class_uses_recursive($class), true))
            ->values()
            ->all();
    }
}
