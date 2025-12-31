<?php

namespace Waterhole\Extend\Api;

use Tobyz\JsonApiServer\Endpoint;
use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;

/**
 * Structure headings JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for structure
 * headings.
 */
class StructureHeadingsResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->endpoints->add(Endpoint\Show::make(), 'show');

        $this->fields->add(Attribute::make('name')->type(Type\Str::make()), 'name');
    }
}
