<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Waterhole\Formatter\Formatter;
use Waterhole\Models\User;

trait HasBody
{
    protected static Formatter $formatter;
    private array $renderCache = [];

    public function mentions(): MorphToMany
    {
        return $this->morphToMany(User::class, 'content', 'mentions');
    }

    public function getBodyAttribute(string $value): string
    {
        return static::$formatter->unparse($value);
    }

    public function getBodyHtmlAttribute(): HtmlString
    {
        return $this->render(Auth::user());
    }

    public function getParsedBodyAttribute(): string
    {
        return $this->attributes['body'];
    }

    public function setBodyAttribute(string $value)
    {
        $context = ['model' => $this];

        $this->attributes['body'] = $value ? static::$formatter->parse($value, $context) : null;
    }

    public function setParsedBodyAttribute(string $value)
    {
        $this->attributes['body'] = $value;
    }

    public function render(User $actor = null): HtmlString
    {
        $key = $actor->id ?? 0;

        if (! isset($this->renderCache[$key])) {
            $context = ['model' => $this, 'actor' => $actor];

            $this->renderCache[$key] = static::$formatter->render($this->parsedBody, $context);
        }

        return new HtmlString($this->renderCache[$key]);
    }

    public static function getFormatter(): Formatter
    {
        return static::$formatter;
    }

    public static function setFormatter(Formatter $formatter)
    {
        static::$formatter = $formatter;
    }
}
