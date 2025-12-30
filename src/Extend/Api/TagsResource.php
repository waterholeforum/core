<?php

namespace Waterhole\Extend\Api;

use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Field\ToOne;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;

/**
 * Tags JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for tags.
 */
class TagsResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->fields
            ->add('name', Attribute::make('name')->type(Type\Str::make()))

            ->add('taxonomy', ToOne::make('taxonomy')->includable());
    }
}
