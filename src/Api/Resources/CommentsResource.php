<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\CommentsResource as CommentsResourceExtender;
use Waterhole\Models\Comment;

class CommentsResource extends ExtendableResource
{
    public function __construct(CommentsResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'comments';
    }

    public function newModel(Context $context): object
    {
        return new Comment();
    }
}
