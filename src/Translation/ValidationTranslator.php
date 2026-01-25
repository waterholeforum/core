<?php

namespace Waterhole\Translation;

use Illuminate\Contracts\Translation\Translator as TranslatorContract;
use Illuminate\Support\Traits\ForwardsCalls;
use Waterhole\Waterhole;

final class ValidationTranslator implements TranslatorContract
{
    use ForwardsCalls;

    public function __construct(protected TranslatorContract $baseTranslator) {}

    public function parseKey($key): array
    {
        if (Waterhole::isWaterholeRoute() && str_starts_with($key, 'validation.')) {
            $key = "waterhole::$key";
        }

        return $this->baseTranslator->parseKey($key);
    }

    public function get($key, array $replace = [], $locale = null)
    {
        return $this->baseTranslator->get($key, $replace, $locale);
    }

    public function choice($key, $number, array $replace = [], $locale = null)
    {
        return $this->baseTranslator->choice($key, $number, $replace, $locale);
    }

    public function getLocale()
    {
        return $this->baseTranslator->getLocale();
    }

    public function setLocale($locale)
    {
        $this->baseTranslator->setLocale($locale);
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->baseTranslator, $method, $parameters);
    }
}
