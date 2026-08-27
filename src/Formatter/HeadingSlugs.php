<?php

namespace Waterhole\Formatter;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use s9e\TextFormatter\Configurator;
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
     *
     * Use Litedown's normal heading ID generation, but replace its
     * ASCII-only slugger with a Unicode-aware implementation.
     */
    public static function configure(Configurator $config): void
    {
        $config->Litedown->addHeadersId(static::PREFIX);

        foreach (range(1, 6) as $level) {
            $tag = $config->tags['H' . $level];

            if (!$tag) {
                continue;
            }

            static::replaceSlugFilter($tag);
        }
    }

    /**
     * Replace Litedown's default ASCII-only slug filter.
     */
    protected static function replaceSlugFilter($tag): void
    {
        $filterChain = $tag->filterChain;

        /*
         * Litedown::addHeadersId() appends its Slugger as the final
         * filter in the chain:
         *
         * Slugger::setTagSlug($tag, $innerText)
         *
         * Remove that filter before adding our Unicode-aware version.
         */
        if (count($filterChain) > 0) {
            $last = count($filterChain) - 1;

            if (
                $filterChain[$last]->getCallback()
                === 's9e\\TextFormatter\\Plugins\\Litedown\\Parser\\Slugger::setTagSlug'
            ) {
                $filterChain->delete($last);
            }
        }

        /*
         * Important:
         *
         * TagFilter defaults to passing only $tag.
         * We explicitly declare both $tag and $innerText because
         * setTagSlug() needs the heading text as its second argument.
         */
        $filter = $filterChain->append(
            static::class . '::setTagSlug($tag, $innerText)'
        );

        $filter->setJS(<<<'JS'
function filterTag(tag, innerText)
{
    let slug = innerText.toLowerCase();

    // Keep Unicode letters and numbers.
    slug = slug.replace(/[^\p{L}\p{N}]+/gu, '-');

    // Remove leading/trailing separators.
    slug = slug.replace(/^-+/, '').replace(/-+$/, '');

    if (slug !== '')
    {
        tag.setAttribute('slug', slug);
    }
}
JS);
    }

    /**
     * Generate a Unicode-safe heading slug.
     *
     * Examples:
     *
     * PHP 設定       -> php-設定
     * 中文設定        -> 中文設定
     * PHP 8.5 設定   -> php-8-5-設定
     * Hello, 世界!   -> hello-世界
     */
    public static function setTagSlug(
        ParserTag $tag,
        string $innerText,
    ): void {
        $slug = trim($innerText);

        if ($slug === '') {
            return;
        }

        /*
         * Unicode-aware lowercase.
         *
         * mb_strtolower() is preferred because it handles Unicode
         * case conversion correctly.
         */
        if (function_exists('mb_strtolower')) {
            $slug = mb_strtolower($slug, 'UTF-8');
        } else {
            $slug = strtolower($slug);
        }

        /*
         * Keep Unicode letters (\p{L}) and numbers (\p{N}).
         * Everything else becomes a single "-".
         */
        $slug = preg_replace(
            '/[^\p{L}\p{N}]+/u',
            '-',
            $slug,
        );

        if ($slug === null) {
            return;
        }

        $slug = trim($slug, '-');

        if ($slug !== '') {
            $tag->setAttribute('slug', $slug);
        }
    }

    /**
     * Extract headings from parsed XML.
     */
    public static function extractHeadings(
        ?string $xml,
        array $levels = [2, 3],
    ): Collection {
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

        $query = collect($levels)
            ->map(fn($level) => "//H{$level}[@slug]")
            ->implode(' | ');

        return collect($dom->query($query))
            ->map(function ($heading) {
                $slug = trim($heading->getAttribute('slug'));

                $text = trim(
                    preg_replace(
                        '/\s+/',
                        ' ',
                        remove_formatting(
                            '<r>' . $heading->C14N() . '</r>',
                        ),
                    ),
                );

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
            $xml = Utils::replaceAttributes(
                $xml,
                "H$level",
                fn(array $attributes) => Arr::except(
                    $attributes,
                    'slug',
                ),
            );
        }

        return $xml;
    }
}
