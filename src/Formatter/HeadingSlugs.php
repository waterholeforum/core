<?php

namespace Waterhole\Formatter;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Configurator\Items\Tag;
use s9e\TextFormatter\Parser\Tag as ParserTag;
use s9e\TextFormatter\Utils;
use s9e\TextFormatter\Utils\ParsedDOM;
use Throwable;

use function Waterhole\remove_formatting;

abstract class HeadingSlugs
{
    public const PREFIX = 'content-';

    /**
     * Formatter configuration callback.
     */
    public static function configure(Configurator $config): void
    {
        $config->Litedown->addHeadersId(static::PREFIX);

        foreach (range(1, 6) as $level) {
            self::addSlugFilter($config->tags['H' . $level]);
        }
    }

    /**
     * Add a Unicode-aware slug filter after Litedown's ASCII-only filter.
     */
    private static function addSlugFilter(Tag $tag): void
    {
        $filter = $tag->filterChain->append(static::class . '::setTagSlug($tag, $innerText)');

        $filter->setJS(<<<'JS'
            function filterTag(tag, innerText)
            {
                const slug = innerText
                    .toLowerCase()
                    .replace(/[^\p{L}\p{N}]+/gu, '-')
                    .replace(/^-|-$/g, '');

                if (slug !== '') {
                    tag.setAttribute('slug', slug);
                }
            }
            JS);
    }

    public static function setTagSlug(ParserTag $tag, string $innerText): void
    {
        $slug = trim(preg_replace('/[^\p{L}\p{N}]+/u', '-', Str::lower($innerText)) ?? '', '-');

        if ($slug !== '') {
            $tag->setAttribute('slug', $slug);
        }
    }

    /**
     * Extract headings from parsed XML.
     */
    public static function extractHeadings(?string $xml, array $levels = [2, 3]): Collection
    {
        if (!$xml) {
            return collect();
        }

        if (!$levels) {
            return collect();
        }

        try {
            $dom = ParsedDOM::loadXML($xml);
        } catch (Throwable) {
            return collect();
        }

        $query = collect($levels)->map(fn($level) => "//H{$level}[@slug]")->implode(' | ');

        return collect($dom->query($query))
            ->map(function ($heading) {
                $slug = trim($heading->getAttribute('slug'));
                $text = trim(preg_replace(
                    '/\s+/',
                    ' ',
                    remove_formatting('<r>' . $heading->C14N() . '</r>'),
                ));

                return [
                    'level' => strtolower($heading->nodeName),
                    'id' => static::PREFIX . $slug,
                    'text' => $text,
                ];
            })
            ->where('id', '!=', static::PREFIX)
            ->where('text', '!=', '')
            ->values();
    }

    /**
     * Remove heading slugs from parsed XML.
     */
    public static function removeHeadingSlugs(
        ?string $xml,
        array $levels = [1, 2, 3, 4, 5, 6],
    ): string {
        if (!$xml) {
            return (string) $xml;
        }

        foreach ($levels as $level) {
            $xml = Utils::replaceAttributes($xml, "H$level", fn(array $attributes) => Arr::except(
                $attributes,
                'slug',
            ));
        }

        return $xml;
    }
}
