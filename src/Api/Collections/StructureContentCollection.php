<?php

namespace Waterhole\Api\Collections;

use Tobyz\JsonApiServer\Context;
use Tobyz\JsonApiServer\Resource\Collection;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\StructureHeading;
use Waterhole\Models\StructureLink;

class StructureContentCollection implements Collection
{
    public function name(): string
    {
        return 'structureContent';
    }

    public function resources(): array
    {
        return ['channels', 'pages', 'structureHeadings', 'structureLinks'];
    }

    public function resource(object $model, Context $context): ?string
    {
        return match (true) {
            $model instanceof Channel => 'channels',
            $model instanceof Page => 'pages',
            $model instanceof StructureHeading => 'structureHeadings',
            $model instanceof StructureLink => 'structureLinks',
            default => null,
        };
    }

    public function endpoints(): array
    {
        return [];
    }
}
