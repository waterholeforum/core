<?php

namespace Waterhole\Providers;

use Astrotomic\Twemoji\Twemoji;
use Illuminate\Support\ServiceProvider;
use Waterhole\Extend;

class TwemojiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (config('waterhole.design.twemoji_base')) {
            Extend\Emoji::provide([static::class, 'twemoji']);
        }
    }

    public static function twemoji(string $text, array $attributes = []): string
    {
        $attributes['class'] = 'twemoji ' . ($attributes['class'] ?? '');

        $attributes = array_merge(
            [
                'width' => '',
                'height' => '',
                // Don't use loading="lazy" because Safari has a bug which will
                // cause the emoji to flicker when navigating with Turbo.
                // https://bugs.webkit.org/show_bug.cgi?id=248025
                'loading' => '',
            ],
            $attributes,
        );

        return Twemoji::text($text)
            ->base(config('waterhole.design.twemoji_base'))
            ->toHtml(null, $attributes);
    }
}
