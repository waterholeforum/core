<?php

namespace Waterhole\Extend;

/**
 * An extender to register formatting callbacks.
 *
 * Waterhole uses the [TextFormatter](https://github.com/s9e/TextFormatter)
 * library to safely format markup in posts and comments. You can hook in to add
 * or remove formatting syntax and change rendered HTML.
 */
abstract class Formatter
{
    /**
     * Add a configuration callback to the formatter.
     */
    public static function configure(callable $callback): void
    {
        app('waterhole.formatter')->configure($callback);
    }

    /**
     * Add a parsing callback to the formatter.
     *
     * In the parsing phase, user-submitted text is parsed into an XML document
     * for storage in the database. Here you can perform runtime configuration
     * on the parser, or make manual alterations to the `$text` before it is
     * parsed.
     */
    public static function parsing(callable $callback): void
    {
        app('waterhole.formatter')->parsing($callback);
    }

    /**
     * Add a rendering callback to the formatter.
     *
     * The rendering phase is when the XML document stored in the database is
     * transformed into HTML. Here you can perform runtime configuration on
     * the renderer, or make manual alterations to the `$xml` document before
     * it is rendered.
     */
    public static function rendering(callable $callback): void
    {
        app('waterhole.formatter')->rendering($callback);
    }
}
