<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\ChannelsResource as ChannelsResourceExtender;
use Waterhole\Models\Channel;

class ChannelsResource extends ExtendableResource
{
    public function __construct(ChannelsResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'channels';
    }

    public function newModel(Context $context): object
    {
        return new Channel();
    }
}
