<?php

namespace Waterhole\Translation;

use Countable;
use Illuminate\Contracts\Translation\Loader;
use Illuminate\Contracts\Translation\Translator as TranslatorContract;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\MessageSelector;
use Illuminate\Translation\Translator as BaseTranslator;
use Major\Fluent\Bundle\FluentBundle;

/**
 * Translator decorator that adds support for Fluent translations.
 *
 * This class is adapted from the `laravel-fluent` package. This version of the
 * class adds support for loading namespaced translations from .ftl files, and
 * allowing them to be overridden by the consumer. It also adds support for
 * adding functions to FluentBundle instances.
 *
 * @link https://github.com/jrmajor/laravel-fluent/pull/2
 *
 * @author Jeremiah Major
 * @license MIT
 */
final class FluentTranslator implements TranslatorContract
{
    /** @var array<string, array<string, FluentBundle|false>> */
    private array $loaded = [];

    public function __construct(
        protected BaseTranslator $baseTranslator,
        protected Filesystem $files,
        protected string $path,
        protected string $locale,
        protected string $fallback,
        /** @var array{strict: bool, useIsolating: bool, allowOverrides: bool} */
        protected array $bundleOptions,
        protected array $functions = []
    ) {
    }

    public function hasForLocale(string $key, ?string $locale = null): bool
    {
        return $this->has($key, $locale, false);
    }

    public function has(string $key, ?string $locale = null, bool $fallback = true): bool
    {
        return $this->get($key, [], $locale, $fallback) !== $key;
    }

    /**
     * @param  string  $key
     * @param  array<string, mixed>  $replace
     * @param  ?string  $locale
     * @return string|array<string, mixed>
     */
    public function get($key, array $replace = [], $locale = null, bool $fallback = true): string|array
    {
        $locale ??= $this->locale;

        [$namespace, $group, $item] = $this->parseKey($key);

        $message = $this->getBundle($namespace, $locale, $group)?->message($item, $replace);

        if ($fallback && $this->fallback !== $locale) {
            $message ??= $this->getBundle($namespace, $this->fallback, $group)?->message($item, $replace);
        }

        return $message ?? $this->baseTranslator->get(...func_get_args());
    }

    private function getBundle(?string $namespace, string $locale, string $group): ?FluentBundle
    {
        return ($this->loaded[$namespace][$locale][$group] ?? $this->loadFtl($namespace, $locale, $group)) ?: null;
    }

    private function loadFtl(?string $namespace, string $locale, string $group): ?FluentBundle
    {
        if (is_null($namespace) || $namespace === '*') {
            $bundle = $this->loadPath($this->path, $locale, $group);
        } else {
            $bundle = $this->loadNamespaced($locale, $group, $namespace);
        }

        return ($this->loaded[$namespace][$locale][$group] = $bundle) ?: null;
    }

    protected function loadPath(string $path, string $locale, string $group): FluentBundle|false
    {
        if ($this->files->exists($full = "{$path}/{$locale}/{$group}.ftl")) {
            $bundle = new FluentBundle($locale, ...$this->bundleOptions);

            foreach ($this->functions as $name => $function) {
                $bundle->addFunction($name, $function);
            }

            return $bundle->addFtl($this->files->get($full));
        }

        return false;
    }

    protected function loadNamespaced(string $locale, string $group, string $namespace): FluentBundle|false
    {
        $hints = $this->getLoader()->namespaces();

        if (isset($hints[$namespace])) {
            if ($bundle = $this->loadPath($hints[$namespace], $locale, $group)) {
                if ($this->bundleOptions['allowOverrides'] ?? false) {
                    return $this->loadNamespaceOverrides($bundle, $locale, $group, $namespace);
                }

                return $bundle;
            }
        }

        return false;
    }

    protected function loadNamespaceOverrides(FluentBundle $bundle, $locale, $group, $namespace): FluentBundle
    {
        if ($this->files->exists($full = "{$this->path}/vendor/{$namespace}/{$locale}/{$group}.ftl")) {
            return $bundle->addFtl($this->files->get($full));
        }

        return $bundle;
    }

    /**
     * @param  string  $key
     * @param  Countable|int|array<mixed, mixed>  $number
     * @param  array<string, mixed>  $replace
     * @param  ?string  $locale
     * @return string
     */
    public function choice($key, $number, array $replace = [], $locale = null)
    {
        return $this->baseTranslator->choice(...func_get_args());
    }

    /**
     * @param  array<mixed, mixed>  $lines
     */
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

    /**
     * @return string[]
     */
    public function parseKey(string $key): array
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
     * @param  string  $locale
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
