<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\StructureResource as StructureResourceExtender;
use Waterhole\Models\Structure;

class StructureResource extends ExtendableResource
{
    public function __construct(StructureResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'structure';
    }

    public function newModel(Context $context): object
    {
        return new Structure();
    }
}
