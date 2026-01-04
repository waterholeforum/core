<?php

namespace Waterhole\Providers;

use Closure;
use Illuminate\Contracts\Translation\Translator as TranslatorContract;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Waterhole\Translation\FluentTranslator;
use Waterhole\Translation\ValidationTranslator;

class TranslationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'waterhole');

        // Extend the Laravel translator to load validation messages from
        // the `waterhole` namespace if this is a Waterhole request. This way
        // we can provide comprehensive translations in the Waterhole package
        // without the user having to manually load them into their skeleton.
        $this->app->extend('translator', function (TranslatorContract $translator) {
            return new ValidationTranslator($translator);
        });

        // On top of that, extend the translator to support loading Fluent
        // translations.
        $this->app->extend(
            'translator',
            fn(TranslatorContract $translator, Application $app) => new FluentTranslator(
                baseTranslator: $translator,
                files: $app['files'],
                path: $app['path.lang'],
                locale: $app->getLocale(),
                fallback: $app->getFallbackLocale(),
                bundleOptions: ['allowOverrides' => true],
                cachePath: config('app.debug') ? null : $app->storagePath('waterhole/translations'),
                functions: [
                    'COMPACT_NUMBER' => Closure::fromCallable('Waterhole\\compact_number'),
                ],
            ),
        );

        $this->app->alias('translator', FluentTranslator::class);
    }
}
