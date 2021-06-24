<?php

/*
 * This file is part of Waterhole.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Waterhole\Locale;

use Waterhole\Models\User;
use Tobyz\Fluent\Bundle;
use Tobyz\Fluent\Resource;

class LocaleRegistry
{
    private array $locales = [];
    private array $translations = [];
    private array $js = [];
    private array $css = [];
    private array $bundles = [];
    private array $functions = [];
    private string $defaultLocale = 'en';

    public function addLocale(string $locale, string $name): void
    {
        $this->locales[$locale] = $name;
    }

    public function getLocales(): array
    {
        return $this->locales;
    }

    public function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }

    public function setDefaultLocale(string $defaultLocale): void
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function localeFor(?User $user): string
    {
        if ($user) {
            $locale = $user->preferredLocale();

            if (isset($this->locales[$locale])) {
                return $locale;
            }
        }

        return $this->defaultLocale;
    }

    public function addFunction($name, $callback)
    {
        $this->functions[$name] = $callback;
    }

    public function getBundle(string $locale): Bundle
    {
        if (! isset($this->bundles[$locale])) {
            $this->bundles[$locale] = new Bundle($locale, [
                'functions' => $this->functions
            ]);

            foreach ($this->getTranslations($locale) as $file) {
                $this->bundles[$locale]->addResource(new Resource(file_get_contents($file)));
            }
        }

        return $this->bundles[$locale];
    }

    public function addTranslations(string $locale, string $file): void
    {
        $this->translations[$locale][] = $file;
    }

    public function getTranslations(string $locale): array
    {
        return $this->translations[$locale] ?? [];
    }

    public function addJs(string $locale, string $file): void
    {
        $this->js[$locale][] = $file;
    }

    public function getJs(string $locale): array
    {
        return $this->js[$locale] ?? [];
    }

    public function addCss(string $locale, string $file): void
    {
        $this->css[$locale][] = $file;
    }

    public function getCss(string $locale): array
    {
        return $this->css[$locale] ?? [];
    }
}
