<?php

namespace Waterhole\Views;

abstract class TurboStream
{
    public static function replace($component): ?string
    {
        if (! $id = $component->id()) {
            return null;
        }

        return static::stream($component, 'replace', $id);
    }

    public static function remove($component): ?string
    {
        if (! $id = $component->id()) {
            return null;
        }

        return <<<html
            <turbo-stream action="remove" target="$id"></turbo-stream>
        html;
    }

    public static function append($component, string $target): string
    {
        return static::stream($component, 'append', $target);
    }

    public static function prepend($component, string $target): string
    {
        return static::stream($component, 'prepend', $target);
    }

    private static function stream($component, string $action, string $target)
    {
        $content = static::renderComponent($component);

        return <<<html
            <turbo-stream action="$action" target="$target">
                <template>
                    $content
                </template>
            </turbo-stream>
        html;
    }

    private static function renderComponent($component)
    {
        return $component->resolveView()->with($component->data())->render();
    }
}
