<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\StructureHeadingsResource as StructureHeadingsResourceExtender;
use Waterhole\Models\StructureHeading;

class StructureHeadingsResource extends ExtendableResource
{
    public function __construct(StructureHeadingsResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'structureHeadings';
    }

    public function newModel(Context $context): object
    {
        return new StructureHeading();
    }
}
