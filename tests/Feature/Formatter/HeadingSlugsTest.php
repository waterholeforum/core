<?php

use Waterhole\Formatter\Formatter;

test('renders unicode heading IDs', function (string $heading, string $id) {
    $formatter = app(Formatter::class);

    $formatter->flush();

    $xml = $formatter->parse("## $heading");

    expect($formatter->render($xml))->toContain("id=\"content-$id\"");
})->with([
    ['PHP 設定', 'php-設定'],
    ['中文設定', '中文設定'],
]);
