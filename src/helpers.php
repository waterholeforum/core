<?php

namespace Waterhole;

use Closure;
use Illuminate\Support\HtmlString;
use Illuminate\View\AnonymousComponent;
use Major\Fluent\Formatters\Number\NumberFormatter;
use Major\Fluent\Formatters\Number\Options;
use Waterhole\Extend\Emoji;
use Waterhole\Models\User;
use Waterhole\Support\Text;

/**
 * Format a number.
 */
function format_number(float $number, array $options = []): string
{
    return (new NumberFormatter(app()->getLocale()))->format($number, new Options(...$options));
}

/**
 * Format a number in compact notation.
 */
function compact_number(float $number): string
{
    if ($number >= 100) {
        $key = 'waterhole::system.compact-number-1' . str_repeat('0', floor(log10($number)));

        if (($format = __($key, [], app()->getLocale())) !== $key) {
            [$numberFormat, $unit] = str_split($format, strrpos($format, '0') + 1);
            $split = explode('.', $numberFormat);
            $digits = strlen($split[0]);
            $fractionDigits = count($split) > 1 ? strlen($split[1]) : 0;
            $threshold = pow(10, $digits);

            while ($number >= $threshold) {
                $number /= 10;
            }

            $formattedNumber = (new NumberFormatter(app()->getLocale()))->format(
                $number,
                new Options(maximumFractionDigits: $fractionDigits),
            );

            return $formattedNumber . $unit;
        }
    }

    return (string) $number;
}

/**
 * Replace Emoji characters in a text string.
 */
function emojify(string $text, array $attributes = []): HtmlString|string
{
    return Emoji::emojify($text, $attributes);
}

/**
 * Truncate a string, handing HTML tags and words correctly.
 */
function truncate_html(string $html, int $limit, string $end = '...'): string
{
    return Text::truncate($html, $limit, [
        'exact' => false,
        'html' => true,
        'ellipsis' => $end,
    ]);
}

/**
 * Get the best contrast color for text on a background color.
 */
function get_contrast_color(string $hex): string
{
    $hex = ltrim($hex, '#');
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    $yiq = ($r * 299 + $g * 587 + $b * 114) / 1000;

    return $yiq >= 128 ? '#000' : '#fff';
}

/**
 * Resolve a collection of services from the container.
 *
 * Items that do not exist will be logged and skipped.
 */
function resolve_all(array $names, array ...$parameters): array
{
    return array_filter(
        array_map(fn($name) => rescue(fn() => resolve($name, ...$parameters)), $names),
    );
}

function return_field(string $default = null): string
{
    return '<input type="hidden" name="return" value="' .
        old('return', request('return', $default)) .
        '">';
}

function username(?User $user): string
{
    return $user->name ?? __('waterhole::system.deleted-user');
}

function user_variables(?User $user): array
{
    return [
        'userName' => username($user),
    ];
}

function build_components(array $components, array $data = []): array
{
    return array_map(function ($component) use ($data) {
        if ($component instanceof Closure) {
            $component = app()->call($component, $data);
        }
        if (is_object($component)) {
            return $component;
        } elseif (class_exists($component)) {
            return resolve($component, $data);
        } elseif (view()->exists($component)) {
            return resolve(AnonymousComponent::class, ['view' => $component, 'data' => $data]);
        }
    }, $components);
}
