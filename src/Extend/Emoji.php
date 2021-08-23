<?php

namespace Waterhole\Extend;

use Illuminate\Support\HtmlString;

class Emoji
{
    public static $emojify;

    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function register()
    {
        static::$emojify = $this->callback;
    }

    public static function emojify(string $text, array $attributes = []): HtmlString|string
    {
        if (isset(static::$emojify)) {
            return new HtmlString((static::$emojify)($text, $attributes));
        }

        return $text;
    }
}
