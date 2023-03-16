<?php

namespace Waterhole\Search;

interface EngineInterface
{
    public function search(
        string $q,
        int $limit,
        int $offset = 0,
        string $sort = null,
        array $channelIds = [],
    ): Results;
}
