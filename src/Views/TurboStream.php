<?php

namespace Waterhole\Views;

use Exception;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

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
        if (!($id = static::getId($component))) {
            return null;
        }

        return static::stream($component, 'replace', $id);
    }

    /**
     * Make a Turbo Stream to remove a streamable component.
     */
    public static function remove(Component $component): ?string
    {
        if (!($id = static::getId($component))) {
            return null;
        }

        // TODO: remove <template> after turbo bug is fixed
        return <<<html
            <turbo-stream action="remove" target="$id">
                <template></template>
            </turbo-stream>
        html;
    }

    /**
     * Make a Turbo Stream to append a component to a target.
     */
    public static function append(Component $component, string $target): string
    {
        return static::stream($component, 'append', $target);
    }

    /**
     * Make a Turbo Stream to prepend a component to a target.
     */
    public static function prepend(Component $component, string $target): string
    {
        return static::stream($component, 'prepend', $target);
    }

    /**
     * Make a Turbo Stream to insert a component before a target.
     */
    public static function before(Component $component, string $target): string
    {
        return static::stream($component, 'before', $target);
    }

    /**
     * Make a Turbo Stream to insert a component after a target.
     */
    public static function after(Component $component, string $target): string
    {
        return static::stream($component, 'after', $target);
    }

    private static function stream(Component $component, string $action, string $target): string
    {
        $content = Blade::renderComponent($component);

        return <<<html
            <turbo-stream action="$action" target="$target">
                <template>
                    $content
                </template>
            </turbo-stream>
        html;
    }

    private static function getId(Component $component)
    {
        if (!method_exists($component, 'id')) {
            throw new Exception(get_class($component) . ' is not streamable');
        }

        return $component->id();
    }
}
