<?php

namespace Waterhole\Formatter;

use Illuminate\Support\Facades\Storage;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Utils;

/**
 * Upload parsing utilities.
 */
abstract class FormatUploads
{
    public const PROTOCOL = 'upload://';

    /**
     * Formatter configuration callback.
     */
    public static function configure(Configurator $config): void
    {
        $config->tags['IMG']->attributes
            ->add('width', ['required' => false])
            ->filterChain->append('#uint');

        $config->tags['IMG']->attributes
            ->add('height', ['required' => false])
            ->filterChain->append('#uint');

        $config->tags['IMG']->template = <<<xsl
            <img src="{@src}">
                <xsl:copy-of select="@alt"/>
                <xsl:copy-of select="@title"/>
                <xsl:copy-of select="@width"/>
                <xsl:copy-of select="@height"/>
            </img>
        xsl;
    }

    /**
     * Formatter rendering callback.
     */
    public static function rendering(Renderer $renderer, string &$xml, ?Context $context): void
    {
        $xml = Utils::replaceAttributes($xml, 'IMG', function ($attributes) use ($context) {
            if (str_starts_with($attributes['src'], static::PROTOCOL)) {
                $filename = substr($attributes['src'], strlen(static::PROTOCOL));
                if ($upload = $context?->model?->attachments->firstWhere('filename', $filename)) {
                    $attributes['width'] = $upload->width;
                    $attributes['height'] = $upload->height;
                }
            }
            $attributes['src'] = static::expandUrl($attributes['src']);
            return $attributes;
        });

        $xml = Utils::replaceAttributes($xml, 'URL', function ($attributes) {
            $attributes['url'] = static::expandUrl($attributes['url']);
            return $attributes;
        });
    }

    private static function expandUrl(string $url): string
    {
        if (str_starts_with($url, static::PROTOCOL)) {
            return Storage::disk(config('waterhole.uploads.disk'))->url(
                'uploads/' . substr($url, strlen(static::PROTOCOL)),
            );
        }
        return $url;
    }

    /**
     * Get all the upload filenames that have been used in a piece of content.
     *
     * This is used in the `HasBody` model trait to populate the `uploads`
     * relationship so that it can be eager loaded when the content is
     * displayed, and the rendering function above can make use of the data.
     */
    public static function getAttachedUploads(string $xml): array
    {
        return collect([
            ...Utils::getAttributeValues($xml, 'URL', 'url'),
            ...Utils::getAttributeValues($xml, 'IMG', 'src'),
        ])
            ->filter(fn($url) => str_starts_with($url, static::PROTOCOL))
            ->map(fn($url) => substr($url, strlen(static::PROTOCOL)))
            ->all();
    }
}
