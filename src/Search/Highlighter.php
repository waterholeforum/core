<?php

namespace Waterhole\Search;

use Illuminate\Support\HtmlString;

use function Waterhole\emojify;

class Highlighter
{
    private ?string $re;

    public function __construct(string $q)
    {
        $this->re = $this->buildRegularExpression($q);
    }

    /**
     * Highlight matching words in the text with `<mark>` tags.
     */
    public function highlight(string $text): HtmlString
    {
        if (!$this->re) {
            return new HtmlString(e($text));
        }

        return new HtmlString(
            preg_replace_callback(
                $this->re,
                function (array $matches) {
                    return "<mark>$matches[0]</mark>";
                },
                emojify($text),
            ),
        );
    }

    /**
     * Truncate text surrounding the first match.
     */
    public function truncate(string $text, int $chars = 100): string
    {
        $start = 0;

        if ($this->re) {
            preg_match($this->re, $text, $matches, PREG_OFFSET_CAPTURE);
            if (isset($matches[0][1])) {
                $start = max(0, $matches[0][1] - $chars);
            }
        }

        if ($start > 0) {
            $text = '...' . substr($text, strpos($text, ' ', $start) + 1);
        }

        if (strlen($text) > $chars * 2) {
            $text = substr($text, 0, strrpos(substr($text, 0, $chars * 2), ' ')) . '...';
        }

        return $text;
    }

    private function buildRegularExpression(string $q): ?string
    {
        if (!trim($q)) {
            return null;
        }

        preg_match_all('/"[^"]+"|[\w*]+/', $q, $phrases);

        $phrases = array_map(function ($phrase) {
            $phrase = preg_replace('/^"|"$/', '', $phrase);
            $phrase = preg_quote($phrase);
            $phrase = preg_replace('/\s+/', '\\W+', $phrase);
            $phrase = preg_replace('/\\*/', '\\w+', $phrase);

            return '\b' . $phrase . '\b';
        }, $phrases[0]);

        return '/' . implode('|', $phrases) . '/i';
    }
}
