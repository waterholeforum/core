<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\TaxonomiesResource as TaxonomiesResourceExtender;
use Waterhole\Models\Taxonomy;

class TaxonomiesResource extends ExtendableResource
{
    public function __construct(TaxonomiesResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'taxonomies';
    }

    public function newModel(Context $context): object
    {
        return new Taxonomy();
    }
}
