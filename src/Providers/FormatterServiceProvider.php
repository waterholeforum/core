<?php

namespace Waterhole\Providers;

use Illuminate\Support\ServiceProvider;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Renderer;
use Waterhole\Formatter\Context;
use Waterhole\Formatter\FormatExternalLinks;
use Waterhole\Formatter\FormatMentions;
use Waterhole\Formatter\Formatter;
use Waterhole\Formatter\FormatUploads;
use Waterhole\Formatter\HeadingSlugs;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Page;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class FormatterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('waterhole.formatter', function ($app) {
            $formatter = new Formatter(
                $app->make('files'),
                $app->make('cache.store'),
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

                $this->configureEmoji($config);
            });

            $formatter->rendering(function (Renderer $renderer, string &$xml, ?Context $context) {
                $renderer->setParameter('USER_ID', $context?->user?->id);
                $renderer->setParameter(
                    'USER_GROUPS',
                    $context?->user?->groups->pluck('id')->implode(','),
                );
            });

            $formatter->configure([HeadingSlugs::class, 'configure']);

            $formatter->configure([FormatExternalLinks::class, 'configure']);
            $formatter->rendering([FormatExternalLinks::class, 'rendering']);

            $formatter->configure([FormatMentions::class, 'configure']);
            $formatter->rendering([FormatMentions::class, 'rendering']);

            $formatter->configure([FormatUploads::class, 'configure']);
            $formatter->rendering([FormatUploads::class, 'rendering']);

            return $formatter;
        });

        $this->app->alias('waterhole.formatter', Formatter::class);

        $this->app->singleton('waterhole.formatter.emoji', function ($app) {
            $formatter = new Formatter(
                $app->make('files'),
                $app->make('cache.store'),
                'waterhole.formatter.emoji',
            );

            $formatter->configure($this->configureEmoji(...));

            return $formatter;
        });
    }

    public function boot()
    {
        $formatter = $this->app->make('waterhole.formatter');

        Comment::setFormatter('body', $formatter);
        Page::setFormatter('body', $formatter);
        Post::setFormatter('body', $formatter);
        Channel::setFormatter('description', $formatter);
        Channel::setFormatter('instructions', $formatter);
        User::setFormatter('bio', $formatter);
    }

    private function configureEmoji(Configurator $config): void
    {
        // Allow the <mark> element for highlighting search results
        $config->HTMLElements->allowElement('mark');

        $tag = $config->Emoji->getTag();

        if ($url = config('waterhole.design.emoji_url')) {
            $tag->template = <<<html
                <img alt="{.}" class="emoji" draggable="false" src="$url"/>
            html;
        } else {
            $tag->template = '<span class="emoji"><xsl:value-of select="."/></span>';
        }
    }
}
