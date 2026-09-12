<?php

declare(strict_types=1);

use Waterhole\Search\Highlighter;

test('search excerpts truncate text without a word boundary', function () {
    expect((new Highlighter(''))->truncate(str_repeat('x', 201)))->toBe('...');
});
