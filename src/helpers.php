<?php

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Illuminate\View\Component;
use Major\Fluent\Formatters\Number\NumberFormatter;
use Major\Fluent\Formatters\Number\Options;
use Waterhole\Support\TimeAgo;


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

/**
 * Format a number in compact notation.
 */
function compact_number(int|float $number, string $locale = null): string
{
    if ($number >= 100) {
        $locale ??= app()->getLocale();
        $key = 'waterhole::number.compact-number-1'.str_repeat('0', floor(log10($number)));

        if (($format = __($key, [], $locale)) !== $key) {
            [$numberFormat, $unit] = str_split($format, strrpos($format, '0') + 1);
            $split = explode('.', $numberFormat);
            $digits = strlen($split[0]);
            $fractionDigits = count($split) > 1 ? strlen($split[1]) : 0;
            $threshold = pow(10, $digits);

            while ($number >= $threshold) {
                $number /= 10;
            }

            $formattedNumber = (new NumberFormatter($locale ?? app()->getLocale()))
                ->format($number, new Options(maximumFractionDigits: $fractionDigits));

            return $formattedNumber.$unit;
        }
    }

    return (string) $number;
}

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

/**
 * Render a component instance to HTML.
 */
function render_component(Component $component): string
{
    $data = $component->data();
    $view = value($component->resolveView(), $data);

    if ($view instanceof View) {
        return $view->with($data)->render();
    } elseif ($view instanceof Htmlable) {
        return $view->toHtml();
    } else {
        return view($view, $data)->render();
    }
}

function get_contrast_color(string $hex): string
{
    $r = hexdec(substr($hex, 1, 2));
    $g = hexdec(substr($hex, 3, 2));
    $b = hexdec(substr($hex, 5, 2));
    $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

    return ($yiq >= 128) ? 'black' : 'white';
}

/**
 * Resolve a collection of services from the container.
 *
 * Items that do not exist will be logged and skipped.
 */
function resolve_all(array $names, array ...$parameters): array
{
    return array_filter(
        array_map(fn($name) => rescue(fn() => resolve($name, ...$parameters)), $names)
    );
}
