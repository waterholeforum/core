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

        $this->endpoints->add(Endpoint\Show::make(), 'show');

        $this->fields
            ->add(Attribute::make('name')->type(Type\Str::make()), 'name')

            ->add(
                Attribute::make('iconHtml')
                    ->type(Type\Str::make()->format('html'))
                    ->nullable()
                    ->get(fn(StructureLink $link) => icon($link->icon)),
                'iconHtml',
            )

            ->add(Attribute::make('href')->type(Type\Str::make()->format('uri')), 'href');
    }
}
