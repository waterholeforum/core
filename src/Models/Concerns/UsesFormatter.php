<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Support\HtmlString;
use Waterhole\Console\ReformatCommand;
use Waterhole\Formatter\Context;
use Waterhole\Formatter\Formatter;
use Waterhole\Models\User;
use function Waterhole\remove_formatting;

trait UsesFormatter
{
    /** @var Formatter[] */
    protected static array $formatters = [];

    private array $renderCache = [];

    /**
     * Render an attribute as HTML for the given user.
     */
    public function format(string $attribute, ?User $user = null): HtmlString|string
    {
        $key = $attribute . ':' . ($user->id ?? 0);
        $value = $this->attributes[$attribute] ?? '';

        if (!isset($this->renderCache[$key])) {
            $this->renderCache[$key] = rescue(
                fn() => $value && str_starts_with($value, '<')
                    ? new HtmlString(
                        static::$formatters[$attribute]->render($value, new Context($this, $user)),
                    )
                    : ($value ?:
                    ''),
                '',
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
    public static function setFormatter(string $attribute, Formatter $formatter): void
    {
        static::$formatters[$attribute] = $formatter;

        ReformatCommand::addModelAttribute(static::class, $attribute);
    }

    public function getAttribute($key)
    {
        if (isset(static::$formatters[$key])) {
            return !empty($this->attributes[$key])
                ? static::$formatters[$key]->unparse($this->attributes[$key])
                : null;
        }

        if (str_starts_with($key, 'parsed_')) {
            $attribute = substr($key, 7);

            if (isset(static::$formatters[$attribute])) {
                return $this->attributes[$attribute];
            }
        }

        if (str_ends_with($key, '_html')) {
            $attribute = substr($key, 0, -5);

            if (isset(static::$formatters[$attribute])) {
                return $this->format($attribute);
            }
        }

        if (str_ends_with($key, '_text')) {
            $attribute = substr($key, 0, -5);

            if (isset(static::$formatters[$attribute])) {
                return remove_formatting($this->attributes[$attribute]);
            }
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        if (str_starts_with($key, 'parsed_')) {
            $this->attributes[substr($key, 7)] = $value;
        } elseif ($formatter = static::$formatters[$key] ?? null) {
            $this->attributes[$key] = $value ? $formatter->parse($value, new Context($this)) : '';
        } else {
            return parent::setAttribute($key, $value);
        }

        return $this;
    }
}
