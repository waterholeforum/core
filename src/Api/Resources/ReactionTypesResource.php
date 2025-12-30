<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\ReactionTypesResource as ReactionTypesResourceExtender;
use Waterhole\Models\ReactionType;

class ReactionTypesResource extends ExtendableResource
{
    public function __construct(ReactionTypesResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'reactionTypes';
    }

    public function newModel(Context $context): object
    {
        return new ReactionType();
    }
}
