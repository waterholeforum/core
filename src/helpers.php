<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Tobyz\Fluent\Overlay;
use Waterhole\Support\TimeAgo;



// function fluent_bundle(string $id, string $locale = null)
// {
//     $locales = app('waterhole.locales');
//
//     $locale = $locale ?: $locales->localeFor(Auth::user());
//
//     $bundle = $locales->getBundle($locale);
//
//     if (! $bundle->hasMessage($id)) {
//         $bundle = $locales->getBundle($locales->getDefaultLocale());
//
//         if (! $bundle->hasMessage($id)) {
//             return null;
//         }
//     }
//
//     return $bundle;
// }
//
// function fluent(string $id, array $args = [], string $locale = null): string
// {
//     if ($bundle = fluent_bundle($id, $locale)) {
//         $message = $bundle->getMessage($id);
//
//         return $bundle->formatPattern($message['value'], $args);
//     }
//
//     return $id;
// }
//
// function fluent_html(string $html, string $id, array $args = [], string $locale = null): string
// {
//     if ($bundle = fluent_bundle($id, $locale)) {
//         $message = $bundle->getMessage($id);
//
//         $translation = [
//             'value' => $bundle->formatPattern($message['value'], $args),
//             'attributes' => []
//         ];
//
//         if (isset($message['attributes'])) {
//             foreach ($message['attributes'] as $name => $value) {
//                 $translation['attributes'][$name] = $bundle->formatPattern($value, $args);
//             }
//         }
//
//         return Overlay::translateHtml($html, $translation);
//     }
//
//     return $id;
// }

function time_ago($then): string
{
    $args = TimeAgo::calculate($then);

    return __('waterhole::time.time-ago', $args);
}

function short_time_ago($then): string
{
    $args = TimeAgo::calculate($then);

    return __('waterhole::time.short-time-ago', $args);
}

function relative_time($then): string
{
    $args = TimeAgo::calculate($then);

    return __('waterhole::time.relative-time', $args);
}

function full_time($date): string
{
    if (! $date instanceof DateTime) {
        $date = new DateTime($date);
    }

    return __('waterhole::time.full-time', [
        'date' => new \Tobyz\Fluent\Types\DateTime($date/*, ['timeZone' => 'Australia/Adelaide']*/)
    ]);
}

function search_re(string $q): ?string
{
    if (! trim($q)) {
        return null;
    }

    preg_match_all('/"[^"]+"|[\w*]+/', $q, $phrases);

    $phrases = array_map(function ($phrase) {
        $phrase = preg_replace('/^"|"$/', '', $phrase);
        $phrase = preg_quote($phrase);
        $phrase = preg_replace('/\s+/', '\\W+', $phrase);
        $phrase = preg_replace('/\\*/', '\\w+', $phrase);
        return '\b'.$phrase.'\b';
    }, $phrases[0]);

    return '/'.implode('|', $phrases).'/i';
}

function highlight_words(string $string, string $q): HtmlString
{
    if (! $re = search_re($q)) {
        return new HtmlString(e($string));
    }

    return new HtmlString(
        preg_replace_callback($re, function (array $matches) {
            return "<mark>$matches[0]</mark>";
        }, e($string))
    );
}

function truncate_around(string $text, string $q, int $chars = 100): string
{
    $start = 0;

    if ($re = search_re($q)) {
        preg_match($re, $text, $matches, PREG_OFFSET_CAPTURE);
        if (isset($matches[0][1])) {
            $start = max(0, $matches[0][1] - $chars);
        }
    }

    if ($start > 0) {
        $text = '...'.substr($text, strpos($text, ' ', $start) + 1);
    }

    if (strlen($text) > $chars * 2) {
        $text = substr($text, 0, strrpos(substr($text, 0, $chars * 2), ' ')).'...';
    }

    return $text;
}

function emojify(string $text, array $attributes = []): HtmlString|string
{
    return Waterhole\Extend\Emoji::emojify($text, $attributes);
}
