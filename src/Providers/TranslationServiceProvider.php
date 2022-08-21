<?php

namespace Waterhole\Providers;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\Translator;
use Waterhole\Translation\FluentTranslator;
use Waterhole\Translation\LaravelTranslator;

class TranslationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'waterhole');

        // Extend the Laravel translator to load auth/validation messages from
        // the `waterhole` namespace if this is a Waterhole request. This way
        // we can provide comprehensive translations in the Waterhole package
        // without the user having to manually load them into their skeleton.
        $this->app->extend('translator', function (Translator $translator) {
            $extended = new LaravelTranslator($translator->getLoader(), $translator->getLocale());
            $extended->setFallback($translator->getFallback());

            return $extended;
        });

        // On top of that, extend the translator to support loading Fluent
        // translations.
        $this->app->extend('translator', function (
            LaravelTranslator $translator,
            Application $app,
        ) {
            return new FluentTranslator(
                baseTranslator: $translator,
                files: $app['files'],
                path: $app['path.lang'],
                locale: $app->getLocale(),
                fallback: $app->getFallbackLocale(),
                bundleOptions: ['allowOverrides' => true],
                functions: [
                    'COMPACT_NUMBER' => Closure::fromCallable('Waterhole\\compact_number'),
                ],
            );
        });

        $this->app->alias('translator', FluentTranslator::class);
    }
}
