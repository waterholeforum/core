<?php

use Waterhole\Formatter\Formatter;

test('preserves chinese characters in heading slugs', function () {
    $formatter = app(Formatter::class);

    $formatter->flush();

    $xml = $formatter->parse('## PHP 設定');

    expect($xml)->toContain('slug="php-設定"');
});

test('preserves chinese-only heading slugs', function () {
    $formatter = app(Formatter::class);

    $formatter->flush();

    $xml = $formatter->parse('## 中文設定');

    expect($xml)->toContain('slug="中文設定"');
});
