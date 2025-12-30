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

        $this->endpoints->add('index', Endpoint\Index::make()->defaultSort('position'));

        $this->fields
            ->add('position', Attribute::make('position')->type(Type\Integer::make()))

            ->add('isListed', Attribute::make('isListed')->type(Type\Boolean::make()))

            ->add(
                'content',
                ToOne::make('content')
                    ->collection('structureContent')
                    ->includable(),
            );

        $this->sorts->add('position', SortColumn::make('position'));
    }
}
