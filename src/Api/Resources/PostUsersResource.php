<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\PostUsersResource as PostUsersResourceExtender;
use Waterhole\Models\PostUser;

class PostUsersResource extends ExtendableResource
{
    public function __construct(PostUsersResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'postUsers';
    }

    public function newModel(Context $context): object
    {
        return new PostUser();
    }
}
