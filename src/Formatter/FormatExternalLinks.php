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
        $nofollowAllowlist = config('waterhole.seo.nofollow_allow', []);
        $nofollowRel = config('waterhole.seo.nofollow_rel', 'nofollow ugc');

        $xml = Utils::replaceAttributes($xml, 'URL', function ($attributes) use (
            $baseUrl,
            $basePath,
            $nofollowAllowlist,
            $nofollowRel,
        ) {
            $url = $attributes['url'] ?? '';

            if (!str_starts_with($url, $baseUrl) && !str_starts_with($url, $basePath)) {
                $attributes['target'] = '_blank';

                if (!static::isAllowlisted($url, $nofollowAllowlist)) {
                    $attributes['rel'] = $nofollowRel;
                }
            }

            return $attributes;
        });
    }

    /**
     * Determine whether an external URL should be exempt from nofollow.
     */
    private static function isAllowlisted(string $url, array $allowlist): bool
    {
        $host = parse_url($url, PHP_URL_HOST)
            ?: (str_starts_with($url, '//') ? parse_url("https:$url", PHP_URL_HOST) : null);

        if (!$host) {
            return false;
        }

        $host = strtolower($host);

        foreach ($allowlist as $allowed) {
            $allowed = strtolower($allowed);

            if ($host === $allowed || str_ends_with($host, '.' . $allowed)) {
                return true;
            }
        }

        return false;
    }
}
