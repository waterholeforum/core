<?php

namespace Waterhole\Providers;

use Waterhole\Extend\Script;
use Waterhole\Formatter\Formatter;
use Waterhole\Formatter\Mentions;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Illuminate\Support\ServiceProvider;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Renderer;

class FormatterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('waterhole.formatter', function () {
            $formatter = new Formatter(
                $this->app->storagePath().'/app/formatter',
                $this->app->make('cache.store'),
                'waterhole.formatter'
            );

            $formatter->configure(function (Configurator $config) {
                $config->rootRules->enableAutoLineBreaks();
                $config->urlConfig->allowScheme('mailto');
                $config->Escaper;
                $config->Autoemail;
                $config->Autolink;
                $config->Litedown;
                $config->HTMLEntities;
            });

            $formatter->configure([Mentions::class, 'configure']);

            $formatter->rendering(function (Renderer $renderer, $xml, $context) {
                $renderer->setParameter('USER_ID', $context['actor']->id ?? null);
            });

            $formatter->rendering([Mentions::class, 'rendering']);

            return $formatter;
        });
    }

    public function boot()
    {
        $formatter = $this->app->make('waterhole.formatter');

        Post::setFormatter($formatter);
        Comment::setFormatter($formatter);
    }
}
