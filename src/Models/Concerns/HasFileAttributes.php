<?php

namespace Waterhole\Models\Concerns;

use Closure;
use Waterhole\Models\Attributes\FileAttribute;

/**
 * Methods to associate uploaded files with a model.
 */
trait HasFileAttributes
{
    protected function fileAttribute(
        string $attribute,
        string $directory,
        ?string $disk = null,
        ?Closure $encodeImage = null,
    ): FileAttribute {
        return new FileAttribute($this, $attribute, $directory, $disk, $encodeImage);
    }
}
