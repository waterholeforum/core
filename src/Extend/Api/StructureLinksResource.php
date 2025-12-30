<?php

namespace Waterhole\Extend\Api;

use Tobyz\JsonApiServer\Endpoint;
use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;
use Waterhole\Models\StructureLink;
use function Waterhole\icon;

/**
 * Structure links JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for structure
 * links.
 */
class StructureLinksResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->endpoints->add('show', Endpoint\Show::make());

        $this->fields
            ->add('name', Attribute::make('name')->type(Type\Str::make()))

            ->add(
                'iconHtml',
                Attribute::make('iconHtml')
                    ->type(Type\Str::make()->format('html'))
                    ->nullable()
                    ->get(fn(StructureLink $link) => icon($link->icon)),
            )

            ->add('href', Attribute::make('href')->type(Type\Str::make()->format('uri')));
    }
}
