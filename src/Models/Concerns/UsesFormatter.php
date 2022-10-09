<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Support\HtmlString;
use Waterhole\Formatter\Context;
use Waterhole\Formatter\Formatter;
use Waterhole\Models\User;

trait UsesFormatter
{
    /** @var Formatter[] */
    protected static array $formatters = [];

    private array $renderCache = [];

    /**
     * Render an attribute as HTML for the given user.
     */
    public function format(string $attribute, User $user = null): HtmlString|string
    {
        $key = $attribute . ':' . ($user->id ?? 0);
        $value = $this->attributes[$attribute];

        if (!isset($this->renderCache[$key])) {
            $this->renderCache[$key] = rescue(
                fn() => $value
                    ? new HtmlString(
                        static::$formatters[$attribute]->render($value, new Context($this, $user)),
                    )
                    : '',
                __('waterhole::system.formatter-error'),
            );
        }

        return $this->renderCache[$key];
    }

    /**
     * Set a formatter instance for this model.
     */
    public static function getFormatter(string $attribute): Formatter
    {
        return static::$formatters[$attribute];
    }

    /**
     * Set a formatter instance for this model.
     */
    public static function setFormatter(string $attribute, Formatter $formatter)
    {
        static::$formatters[$attribute] = $formatter;
    }

    public function getAttribute($key)
    {
        if (str_starts_with($key, 'parsed_')) {
            return $this->attributes[substr($key, 7)];
        }

        if (str_ends_with($key, '_html')) {
            return $this->format(substr($key, 0, -5));
        }

        if ($formatter = static::$formatters[$key] ?? null) {
            return !empty($this->attributes[$key])
                ? $formatter->unparse($this->attributes[$key])
                : null;
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        parent::setAttribute($key, $value);

        if (str_starts_with($key, 'parsed_')) {
            $this->attributes[substr($key, 7)] = $value;
        }

        if ($formatter = static::$formatters[$key] ?? null) {
            $this->attributes[$key] = $value ? $formatter->parse($value, new Context($this)) : null;
        }
    }
}
