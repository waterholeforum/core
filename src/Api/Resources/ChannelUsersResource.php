<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\ChannelUsersResource as ChannelUsersResourceExtender;
use Waterhole\Models\ChannelUser;

class ChannelUsersResource extends ExtendableResource
{
    public function __construct(ChannelUsersResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'channelUsers';
    }

    public function newModel(Context $context): object
    {
        return new ChannelUser();
    }
}
