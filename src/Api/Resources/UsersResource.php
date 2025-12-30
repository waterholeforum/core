<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\UsersResource as UsersResourceExtender;
use Waterhole\Models\User;

class UsersResource extends ExtendableResource
{
    public function __construct(UsersResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'users';
    }

    public function newModel(Context $context): object
    {
        return new User();
    }
}
