<?php

namespace Waterhole\Search;

class Results
{
    public function __construct(
        public array $hits,
        public ?int $total = null,
        public bool $exhaustiveTotal = false,
        public array $channelHits = [],
        public ?string $error = null,
    ) {}
}
