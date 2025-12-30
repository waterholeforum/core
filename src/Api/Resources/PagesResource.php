<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\PagesResource as PagesResourceExtender;
use Waterhole\Models\Page;

class PagesResource extends ExtendableResource
{
    public function __construct(PagesResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'pages';
    }

    public function newModel(Context $context): object
    {
        return new Page();
    }
}
