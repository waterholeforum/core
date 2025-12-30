<?php

namespace Waterhole\Extend\Support;

use function Waterhole\build_components;

/**
 * Ordered list of UI components with helpers to resolve instances.
 *
 * Use this when an extender needs ordered components resolved into view
 * instances.
 */
class ComponentList extends OrderedList
{
    public function components(array $data = []): array
    {
        return build_components($this->items(), $data);
    }
}
