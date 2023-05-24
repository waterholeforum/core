<?php

namespace Waterhole\Formatter;

use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Utils;

class FormatExternalLinks
{
    /**
     * Formatter configuration callback.
     */
    public static function configure(Configurator $config): void
    {
        $dom = $config->tags['URL']->template->asDOM();

        foreach ($dom->getElementsByTagName('a') as $a) {
            $a->prependXslCopyOf('@target');
            $a->prependXslCopyOf('@rel');
        }

        $dom->saveChanges();
    }

    /**
     * Formatter rendering callback.
     */
    public static function rendering(Renderer $renderer, string &$xml, ?Context $context): void
    {
        $baseUrl = route('waterhole.home');
        $basePath = '/' . config('waterhole.forum.path');

        $xml = Utils::replaceAttributes($xml, 'URL', function ($attributes) use (
            $baseUrl,
            $basePath,
        ) {
            $url = $attributes['url'] ?? '';

            if (!str_starts_with($url, $baseUrl) && !str_starts_with($url, $basePath)) {
                $attributes['target'] = '_blank';
                $attributes['rel'] = 'nofollow ugc';
            }

            return $attributes;
        });
    }
}
