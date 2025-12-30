<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\ReactionsResource as ReactionsResourceExtender;
use Waterhole\Models\Reaction;

class ReactionsResource extends ExtendableResource
{
    public function __construct(ReactionsResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'reactions';
    }

    public function newModel(Context $context): object
    {
        return new Reaction();
    }
}
