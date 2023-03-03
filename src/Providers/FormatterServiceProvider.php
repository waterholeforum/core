<?php

namespace Waterhole\Providers;

use Illuminate\Support\ServiceProvider;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Renderer;
use Waterhole\Formatter\Context;
use Waterhole\Formatter\FormatMentions;
use Waterhole\Formatter\Formatter;
use Waterhole\Formatter\FormatUploads;
use Waterhole\Models\Channel;
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
                $config->urlConfig->allowScheme('upload');
                $config->Escaper;
                $config->Autoemail;
                $config->Autolink;
                $config->Litedown;
                $config->PipeTables;
                $config->TaskLists;
                $config->Autovideo;
                $config->Autoimage;
            });

            $formatter->rendering(function (Renderer $renderer, string $xml, ?Context $context) {
                $renderer->setParameter('USER_ID', $context->user->id ?? null);
            });

            $formatter->configure([FormatMentions::class, 'configure']);
            $formatter->rendering([FormatMentions::class, 'rendering']);

            $formatter->configure([FormatUploads::class, 'configure']);
            $formatter->rendering([FormatUploads::class, 'rendering']);

            return $formatter;
        });

        $this->app->alias('waterhole.formatter', Formatter::class);
    }

    public function boot()
    {
        $formatter = $this->app->make('waterhole.formatter');

        Comment::setFormatter('body', $formatter);
        Page::setFormatter('body', $formatter);
        Post::setFormatter('body', $formatter);
        Channel::setFormatter('description', $formatter);
        Channel::setFormatter('instructions', $formatter);
    }
}
