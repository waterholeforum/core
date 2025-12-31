<?php

namespace Waterhole\Extend\Api;

use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Field\ToMany;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;

/**
 * Taxonomies JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for taxonomies.
 */
class TaxonomiesResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->fields
            ->add(Attribute::make('name')->type(Type\Str::make()), 'name')

            ->add(ToMany::make('tags')->includable(), 'tags');
    }
}
