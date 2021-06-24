<?php

namespace Waterhole\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\Translator;
use Waterhole\Locale\BaseTranslator;
use Waterhole\Locale\FluentTranslator;

class LocaleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'waterhole');

        // Extend the translator to namespace validation messages
        $this->app->extend('translator', function (Translator $translator) {
            $extended = new BaseTranslator($translator->getLoader(), $translator->getLocale());
            $extended->setFallback($translator->getFallback());

            return $extended;
        });

        // On top of that, extend the translator to support loading .ftl
        $this->app->extend('translator', function (BaseTranslator $translator, Application $app) {
            return new FluentTranslator(
                baseTranslator: $translator,
                files: $app['files'],
                path: $app['path.lang'],
                locale: $app->getLocale(),
                fallback: $app->getFallbackLocale(),
                bundleOptions: [],
            );
        });

        $this->app->alias('translator', FluentTranslator::class);

        // Waterhole::extend([
        //     new Extend\Locale('en', __DIR__.'/../../resources/lang/en', 'English')
        // ]);

        // $this->app->singleton(LocaleRegistry::class, function () {
        //     $locales = new LocaleRegistry();
        //
        //     $locales->addFunction('SHORT_NUMBER', function (array $args, array $opts) {
        //         if (! $args[0] instanceof Number) {
        //             throw new RuntimeException('Invalid argument for SHORT_NUMBER '.$args[0]);
        //         }
        //
        //         return new ShortNumber($args[0]->valueOf());
        //     });
        //
        //     $locales->setDefaultLocale('en');
        //
        //     return $locales;
        // });
        //
        // $this->app->alias(LocaleRegistry::class, 'waterhole.locales');
    }
}
