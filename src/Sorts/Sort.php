<?php

namespace Waterhole\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use ReflectionClass;

abstract class Sort
{
    public function handle(): string
    {
        return Str::kebab((new ReflectionClass($this))->getShortName());
    }

    abstract public function name(): string;

    abstract public function description(): string;

    abstract public function apply($query): void;
}
