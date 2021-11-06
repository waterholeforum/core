<?php

namespace Waterhole\Search;

class SearchResults
{
    public array $hits;
    public ?int $total;
    public bool $exhaustiveTotal;
    public array $channelHits;

    public function __construct(
        array $hits,
        int $total = null,
        bool $exhaustiveTotal = false,
        array $channelHits = [],
    ) {
        $this->hits = $hits;
        $this->total = $total;
        $this->exhaustiveTotal = $exhaustiveTotal;
        $this->channelHits = $channelHits;
    }
}
