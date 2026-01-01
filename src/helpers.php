<?php

namespace Waterhole;

use BladeUI\Icons\Exceptions\SvgNotFound;
use Closure;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\View\AnonymousComponent;
use Illuminate\View\ComponentAttributeBag;
use Major\Fluent\Formatters\Number\NumberFormatter;
use Major\Fluent\Formatters\Number\Options;
use s9e\TextFormatter\Utils;
use Waterhole\Extend\Support\ComponentList;
use Waterhole\Models\User;

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
 * Replace Emoji characters in a plain-text string.
 */
function emojify(?string $text): HtmlString|string
{
    if (!$text) {
        return '';
    }

    $formatter = app('waterhole.formatter.emoji');

    return new HtmlString($formatter->render($formatter->parse($text)));
}

/**
 * Strip the formatting of an intermediate representation and return plain text.
 */
function remove_formatting(?string $xml): string
{
    if (!$xml) {
        return '';
    }

    try {
        return Utils::removeFormatting($xml);
    } catch (Exception $e) {
        return '';
    }
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
        array_map(
            fn($name) => is_object($name) ? $name : rescue(fn() => resolve($name, ...$parameters)),
            $names,
        ),
    );
}

function return_field(?string $default = null): string
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

function build_components(array|string|ComponentList $components, array $data = []): array
{
    if (is_string($components) && class_exists($components)) {
        $components = resolve($components);
    }

    if ($components instanceof ComponentList) {
        $components = $components->items();
    }

    return array_map(function ($component) use ($data) {
        if ($component instanceof Closure) {
            $component = app()->call($component, $data);
        }
        if (is_object($component)) {
            return $component;
        } elseif (class_exists($component)) {
            return $component::resolve($data);
        } elseif (view()->exists($component)) {
            return new AnonymousComponent($component, $data);
        }
    }, (array) $components);
}

function icon(?string $icon, array $attributes = []): string
{
    if (!$icon) {
        return '';
    }

    $attributes['class'] = ($attributes['class'] ?? '') . ' icon';

    if (str_starts_with($icon, 'emoji:')) {
        return sprintf(
            '<span %s>%s</span>',
            new ComponentAttributeBag($attributes),
            emojify(substr($icon, 6)),
        );
    }

    if (str_starts_with($icon, 'file:')) {
        return sprintf(
            '<img src="%s" alt="" %s>',
            e(Storage::disk('public')->url('icons/' . substr($icon, 5))),
            new ComponentAttributeBag($attributes),
        );
    }

    if (str_starts_with($icon, 'svg:')) {
        $icon = substr($icon, 4);
    }

    $attributes['class'] .= " icon-$icon";

    try {
        return svg($icon, $attributes['class'], Arr::except($attributes, 'class'))->toHtml();
    } catch (SvgNotFound $e) {
        if (config('app.debug')) {
            return '<script>console.warn("Icon [' . e($icon) . '] not found")</script>';
        }
    }

    return '';
}

function is_absolute_url(string $path): bool
{
    return str_starts_with($path, 'https://') || str_starts_with($path, 'http://');
}
