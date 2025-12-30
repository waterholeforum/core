<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\ReactionSetsResource as ReactionSetsResourceExtender;
use Waterhole\Models\ReactionSet;

class ReactionSetsResource extends ExtendableResource
{
    public function __construct(ReactionSetsResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'reactionSets';
    }

    public function newModel(Context $context): object
    {
        return new ReactionSet();
    }
}
