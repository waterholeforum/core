<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\GroupsResource as GroupsResourceExtender;
use Waterhole\Models\Group;

class GroupsResource extends ExtendableResource
{
    public function __construct(GroupsResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'groups';
    }

    public function newModel(Context $context): object
    {
        return new Group();
    }
}
