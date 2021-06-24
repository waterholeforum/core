<?php

namespace Waterhole\Locale;

use Illuminate\Contracts\Translation\Loader;
use Illuminate\Contracts\Translation\Translator as TranslatorContract;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\MessageSelector;
use Illuminate\Translation\Translator as BaseTranslator;
use Major\Fluent\Bundle\FluentBundle;
use Waterhole\Waterhole;

/**
 * https://github.com/jrmajor/laravel-fluent/blob/master/src/FluentTranslator.php
 */
class FluentTranslator implements TranslatorContract
{
    /** @var array<string, array<string, array<string, FluentBundle|false>>> */
    protected array $loaded = [];

    public function __construct(
        protected BaseTranslator $baseTranslator,
        protected Filesystem $files,
        protected string $path,
        protected string $locale,
        protected string $fallback,
        protected array $bundleOptions,
    ) { }

    public function hasForLocale(string $key, string $locale = null): bool
    {
        return $this->has($key, $locale, false);
    }

    public function has(string $key, string $locale = null, bool $fallback = true): bool
    {
        return $this->get($key, [], $locale, $fallback) !== $key;
    }

    /**
     * @param string $key
     * @param array<string, mixed> $replace
     * @param ?string $locale
     * @return string|array<string, mixed>
     */
    public function get($key, array $replace = [], $locale = null, bool $fallback = true): string|array
    {
        $locale ??= $this->locale;

        [$namespace, $group, $item] = $this->parseKey($key);

        $message = $this->getBundle($namespace, $group, $locale)?->message($item, $replace);

        if ($fallback && $this->fallback !== $locale) {
            $message ??= $this->getBundle($namespace, $group, $locale)?->message($item, $replace);
        }

        return $message ?? $this->baseTranslator->get(...func_get_args());
    }

    protected function getBundle(?string $namespace, string $group, string $locale): ?FluentBundle
    {
        return ($this->loaded[$namespace][$group][$locale] ?? $this->loadFtl($namespace, $group, $locale)) ?: null;
    }

    protected function loadFtl(?string $namespace, string $group, string $locale): ?FluentBundle
    {
        if (is_null($namespace) || $namespace === '*') {
            $bundle = $this->loadPath($this->path, $locale, $group);
        } else {
            $bundle = $this->loadNamespaced($locale, $group, $namespace);
        }

        return ($this->loaded[$namespace][$group][$locale] = $bundle) ?: null;
    }

    protected function loadPath(string $path, string $locale, string $group): ?FluentBundle
    {
        if ($this->files->exists($full = "{$path}/{$locale}/{$group}.ftl")) {
            return (new FluentBundle($locale, ...$this->bundleOptions))
                ->addFtl($this->files->get($full));
        }

        return null;
    }

    protected function loadNamespaced(string $locale, string $group, string $namespace): ?FluentBundle
    {
        $hints = $this->getLoader()->namespaces();

        if (isset($hints[$namespace])) {
            return $this->loadPath($hints[$namespace], $locale, $group);
        }

        return null;
    }

    public function choice($key, $number, array $replace = [], $locale = null): string
    {
        return $this->baseTranslator->choice(...func_get_args());
    }

    public function addLines(array $lines, string $locale, string $namespace = '*'): void
    {
        $this->baseTranslator->addLines(...func_get_args());
    }

    public function load(string $namespace, string $group, string $locale): void
    {
        $this->baseTranslator->load($namespace, $group, $locale);
    }

    public function addNamespace(string $namespace, string $hint): void
    {
        $this->baseTranslator->addNamespace($namespace, $hint);
    }

    public function addJsonPath(string $path): void
    {
        $this->baseTranslator->addJsonPath($path);
    }

    public function parseKey($key): array
    {
        return $this->baseTranslator->parseKey($key);
    }

    public function getSelector(): MessageSelector
    {
        return $this->baseTranslator->getSelector();
    }

    public function setSelector(MessageSelector $selector): void
    {
        $this->baseTranslator->setSelector($selector);
    }

    public function getLoader(): Loader
    {
        return $this->baseTranslator->getLoader();
    }

    public function locale(): string
    {
        return $this->getLocale();
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale): void
    {
        $this->locale = $locale;
        $this->baseTranslator->setLocale($locale);
    }

    public function getFallback(): string
    {
        return $this->fallback;
    }

    public function setFallback(string $locale): void
    {
        $this->fallback = $locale;
        $this->baseTranslator->setFallback($locale);
    }
}
