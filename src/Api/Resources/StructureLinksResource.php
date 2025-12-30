<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\StructureLinksResource as StructureLinksResourceExtender;
use Waterhole\Models\StructureLink;

class StructureLinksResource extends ExtendableResource
{
    public function __construct(StructureLinksResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'structureLinks';
    }

    public function newModel(Context $context): object
    {
        return new StructureLink();
    }
}
