<?php

namespace Waterhole\Views;

use Illuminate\View\Component;

use function Waterhole\render_component;

abstract class TurboStream
{
    public static function replace(Component $component): ?string
    {
        if (! $id = static::getId($component)) {
            return null;
        }

        return static::stream($component, 'replace', $id);
    }

    public static function remove(Component $component): ?string
    {
        if (! $id = static::getId($component)) {
            return null;
        }

        return <<<html
            <turbo-stream action="remove" target="$id"></turbo-stream>
        html;
    }

    public static function append(Component $component, string $target): string
    {
        return static::stream($component, 'append', $target);
    }

    public static function prepend(Component $component, string $target): string
    {
        return static::stream($component, 'prepend', $target);
    }

    public static function before(Component $component, string $target): string
    {
        return static::stream($component, 'before', $target);
    }

    public static function after(Component $component, string $target): string
    {
        return static::stream($component, 'after', $target);
    }

    private static function stream(Component $component, string $action, string $target): string
    {
        $content = render_component($component);

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
        if (! method_exists($component, 'id')) {
            return null;
        }

        return $component->id();
    }
}
