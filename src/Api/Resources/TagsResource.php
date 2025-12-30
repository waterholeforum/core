<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\TagsResource as TagsResourceExtender;
use Waterhole\Models\Tag;

class TagsResource extends ExtendableResource
{
    public function __construct(TagsResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'tags';
    }

    public function newModel(Context $context): object
    {
        return new Tag();
    }
}
