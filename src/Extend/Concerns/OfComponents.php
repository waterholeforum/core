<?php

namespace Waterhole\Extend\Concerns;

use function Waterhole\build_components;

trait OfComponents
{
    public static function components(array $data = []): array
    {
        return build_components(static::build(), $data);
    }
}
