<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\MentionsResource as MentionsResourceExtender;
use Waterhole\Models\Mention;

class MentionsResource extends ExtendableResource
{
    public function __construct(MentionsResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'mentions';
    }

    public function newModel(Context $context): object
    {
        return new Mention();
    }
}
