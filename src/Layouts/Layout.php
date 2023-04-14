<?php

namespace Waterhole\Layouts;

use Illuminate\Database\Eloquent\Builder;

/**
 * Base class for a Layout.
 */
abstract class Layout
{
    /**
     * The text label for the layout.
     */
    abstract public function label(): string;

    /**
     * The icon representing the layout.
     */
    public function icon(): ?string
    {
        return null;
    }

    /**
     * Class to apply to the wrapper element.
     */
    public function wrapperClass(): ?string
    {
        return null;
    }

    /**
     * The name of the component used to display a post in the layout.
     */
    abstract public function itemComponent(): string;

    /**
     * Apply a scope to the post feed query when this layout is active.
     */
    public function scope(Builder $query): void
    {
    }

    /**
     * Name of the Waterhole\Forms\Field class for configuration of the layout.
     */
    public function configField(): ?string
    {
        return null;
    }
}
