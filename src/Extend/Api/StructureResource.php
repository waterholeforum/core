<?php

namespace Waterhole\Extend\Api;

use Tobyz\JsonApiServer\Endpoint;
use Tobyz\JsonApiServer\Laravel\Sort\SortColumn;
use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Field\ToOne;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;

/**
 * Structure JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for structure.
 */
class StructureResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->endpoints->add(Endpoint\Index::make()->defaultSort('position'), 'index');

        $this->fields
            ->add(Attribute::make('position')->type(Type\Integer::make()), 'position')

            ->add(Attribute::make('isListed')->type(Type\Boolean::make()), 'isListed')

            ->add(ToOne::make('content')->collection('structureContent')->includable(), 'content');

        $this->sorts->add(SortColumn::make('position'), 'position');
    }
}
