<?php

namespace Waterhole\Extend\Api;

use Tobyz\JsonApiServer\Endpoint;
use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;
use Waterhole\Models\Page;
use function Waterhole\icon;

/**
 * Pages JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for pages.
 */
class PagesResource extends Resource
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
                    ->get(fn(Page $page) => icon($page->icon)),
                'iconHtml',
            )

            ->add(
                Attribute::make('bodyHtml')
                    ->type(Type\Str::make()->format('html'))
                    ->nullable(),
                'bodyHtml',
            )

            ->add(Attribute::make('url')->type(Type\Str::make()->format('uri')), 'url');
    }
}
