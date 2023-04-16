<?php

namespace Waterhole\View;

use Exception;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

/**
 * Helper class for making <turbo-stream> elements.
 */
abstract class TurboStream
{
    /**
     * Make a Turbo Stream to replace a streamable component.
     */
    public static function replace(Component $component): ?string
    {
        if (!($class = static::getClassName($component))) {
            return null;
        }

        return static::stream($component, 'replace', ['targets' => ".$class"]);
    }

    /**
     * Make a Turbo Stream to remove a streamable component.
     */
    public static function remove(Component $component): ?string
    {
        if (!($class = static::getClassName($component))) {
            return null;
        }

        return <<<html
            <turbo-stream action="remove" targets=".$class"></turbo-stream>
        html;
    }

    /**
     * Make a Turbo Stream to append a component to a target.
     */
    public static function append(Component $component, string $targets): string
    {
        return static::stream($component, 'append', compact('targets'));
    }

    /**
     * Make a Turbo Stream to prepend a component to a target.
     */
    public static function prepend(Component $component, string $targets): string
    {
        return static::stream($component, 'prepend', compact('targets'));
    }

    /**
     * Make a Turbo Stream to insert a component before a target.
     */
    public static function before(Component $component, string $targets): string
    {
        return static::stream($component, 'before', compact('targets'));
    }

    /**
     * Make a Turbo Stream to insert a component after a target.
     */
    public static function after(Component $component, string $targets): string
    {
        return static::stream($component, 'after', compact('targets'));
    }

    private static function stream(Component $component, string $action, array $attributes): string
    {
        $attributes = new ComponentAttributeBag($attributes);
        $content = Blade::renderComponent($component);

        return <<<html
            <turbo-stream action="$action" $attributes>
                <template>
                    $content
                </template>
            </turbo-stream>
        html;
    }

    private static function getClassName(Component $component)
    {
        if (!method_exists($component, 'streamableClassName')) {
            throw new Exception(get_class($component) . ' is not streamable');
        }

        return $component->streamableClassName();
    }
}
