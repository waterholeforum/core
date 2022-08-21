<?php

namespace Waterhole\Providers;

use Illuminate\Support\ServiceProvider;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Renderer;
use Waterhole\Formatter\Context;
use Waterhole\Formatter\Formatter;
use Waterhole\Formatter\Mentions;
use Waterhole\Models\Comment;
use Waterhole\Models\Page;
use Waterhole\Models\Post;

class FormatterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('waterhole.formatter', function () {
            $formatter = new Formatter(
                $this->app->storagePath() . '/waterhole/formatter',
                $this->app->make('cache.store'),
                'waterhole.formatter',
            );

            $formatter->configure(function (Configurator $config) {
                $config->rootRules->enableAutoLineBreaks();
                $config->urlConfig->allowScheme('mailto');
                $config->Escaper;
                $config->Autoemail;
                $config->Autolink;
                $config->Litedown;
                $config->PipeTables;
                $config->TaskLists;

                // Add the CODE tag to get auto-syntax-highlighting support.
                $config->BBCodes->addFromRepository('CODE', vars: ['style' => 'none']);
            });

            $formatter->rendering(function (Renderer $renderer, string $xml, ?Context $context) {
                $renderer->setParameter('USER_ID', $context->user->id ?? null);
            });

            $formatter->configure([Mentions::class, 'configure']);
            $formatter->rendering([Mentions::class, 'rendering']);

            return $formatter;
        });

        $this->app->alias('waterhole.formatter', Formatter::class);
    }

    public function boot()
    {
        $formatter = $this->app->make('waterhole.formatter');

        Comment::setFormatter($formatter);
        Page::setFormatter($formatter);
        Post::setFormatter($formatter);
    }
}
