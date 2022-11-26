<?php

namespace Waterhole\Extend;

use Illuminate\Support\HtmlString;

/**
 * A service that performs emojification of plain-text strings.
 *
 * By default, no modification is performed, and emoji remain in their Unicode
 * form. But consumers could, for example, register a callback that converts
 * Unicode emoji into `<img>` tags for a set like Twemoji.
 */
abstract class Emoji
{
    private static $emojify;

    /**
     * Provide a callback to perform emojification of plain-text strings.
     */
    public static function provide(?callable $callback)
    {
        static::$emojify = $callback;
    }

    /**
     * Emojify a plain-text string.
     */
    public static function emojify(string $text, array $attributes = []): HtmlString|string
    {
        if (isset(static::$emojify)) {
            return new HtmlString((static::$emojify)($text, $attributes));
        }

        return $text;
    }
}
